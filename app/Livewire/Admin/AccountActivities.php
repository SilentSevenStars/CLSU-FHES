<?php

namespace App\Livewire\Admin;

use App\Models\AccountActivity;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AccountActivities extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filterRole   = 'all';
    public string $dateFrom     = '';
    public string $dateTo       = '';
    public int    $perPage      = 10;

    protected $paginationTheme = 'tailwind';

    public int $totalActivities = 0;
    public int $todayCount      = 0;

    public function mount(): void
    {
        $this->refreshCounters();
    }

    public function updatingSearch():     void { $this->resetPage(); }
    public function updatingPerPage():    void { $this->resetPage(); }
    public function updatingFilterRole(): void { $this->resetPage(); }
    public function updatingDateFrom():   void { $this->resetPage(); }
    public function updatingDateTo():     void { $this->resetPage(); }


    #[On('echo:account-activities,.UserActivityRecorded')]
    public function onNewActivity(): void
    {
        $this->refreshCounters();
    }

    public function clearFilters(): void
    {
        $this->search     = '';
        $this->filterRole = 'all';
        $this->dateFrom   = '';
        $this->dateTo     = '';
        $this->resetPage();
    }

    private function refreshCounters(): void
    {
        $this->totalActivities = AccountActivity::count();
        $this->todayCount      = AccountActivity::whereDate('datetime', today())->count();
    }

    public function render()
    {
        $query = AccountActivity::query()
            ->with(['user.roles', 'user.applicant'])
            ->orderBy('datetime', 'desc');

        if ($this->filterRole !== 'all') {
            $query->whereHas('user.roles', fn($q) =>
                $q->where('name', $this->filterRole)
            );
        }

        if ($this->dateFrom) {
            $query->whereDate('datetime', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('datetime', '<=', $this->dateTo);
        }

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $candidateUserIds = (clone $query)
                ->pluck('user_id')
                ->unique()
                ->values();

            $matchedUserIds = User::with(['roles', 'applicant'])
                ->whereIn('id', $candidateUserIds)
                ->get()
                ->filter(function (User $user) use ($needle) {
                    $roleName = $user->roles->first()?->name ?? '';

                    if ($roleName === 'applicant' && $user->applicant) {
                        $fullName = strtolower(
                            ($user->applicant->first_name  ?? '') . ' ' .
                            ($user->applicant->middle_name ?? '') . ' ' .
                            ($user->applicant->last_name   ?? '')
                        );
                        if (str_contains($fullName, $needle)) {
                            return true;
                        }
                    }

                    $name  = strtolower($user->name  ?? '');
                    $email = strtolower($user->email ?? '');

                    return str_contains($name, $needle)
                        || str_contains($email, $needle);
                })
                ->pluck('id');

            $datetimeMatchedLogIds = (clone $query)
                ->get(['id', 'datetime'])
                ->filter(fn($row) => str_contains(
                    strtolower($row->datetime->format('M d, Y h:i:s A')),
                    $needle
                ))
                ->pluck('id');

            $query->where(function ($q) use ($matchedUserIds, $datetimeMatchedLogIds) {
                $q->whereIn('user_id', $matchedUserIds)
                  ->orWhereIn('id', $datetimeMatchedLogIds);
            });
        }

        $activities = $query->paginate($this->perPage);

        return view('livewire.admin.account-activities', [
            'activities' => $activities,
        ]);
    }
}