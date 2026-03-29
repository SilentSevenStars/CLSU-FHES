<?php

namespace App\Livewire\Admin;

use App\Models\AccountLog;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AccountLogs extends Component
{
    use WithPagination;

    public string $search          = '';
    public string $filterActivity  = 'all'; // 'all' | 'logged in' | 'logged out'
    public string $dateFrom        = '';
    public string $dateTo          = '';
    public int    $perPage         = 10;

    protected $paginationTheme = 'tailwind';

    // ── Real-time counters ────────────────────────────────────────────────────
    public int $totalLogs   = 0;
    public int $loginCount  = 0;
    public int $logoutCount = 0;
    public int $todayCount  = 0;

    public function mount(): void
    {
        $this->refreshCounters();
    }

    public function updatingSearch():         void { $this->resetPage(); }
    public function updatingPerPage():        void { $this->resetPage(); }
    public function updatingFilterActivity(): void { $this->resetPage(); }
    public function updatingDateFrom():       void { $this->resetPage(); }
    public function updatingDateTo():         void { $this->resetPage(); }

    /**
     * Fired by Laravel Echo when UserActivityLogged broadcast arrives.
     * Livewire auto re-renders after this runs.
     */
    #[On('echo:account-logs,.UserActivityLogged')]
    public function onNewLog(): void
    {
        $this->refreshCounters();
    }

    public function clearFilters(): void
    {
        $this->search         = '';
        $this->filterActivity = 'all';
        $this->dateFrom       = '';
        $this->dateTo         = '';
        $this->resetPage();
    }

    private function refreshCounters(): void
    {
        $this->totalLogs   = AccountLog::count();
        $this->loginCount  = AccountLog::where('activity', 'logged in')->count();
        $this->logoutCount = AccountLog::where('activity', 'logged out')->count();
        $this->todayCount  = AccountLog::whereDate('datetime', today())->count();
    }

    public function render()
    {
        // ── Base query – DB-level filters only (activity + date range) ────────
        $query = AccountLog::query()
            ->with(['user.roles', 'user.applicant'])
            ->orderBy('datetime', 'desc');

        if ($this->filterActivity !== 'all') {
            $query->where('activity', $this->filterActivity);
        }

        if ($this->dateFrom) {
            $query->whereDate('datetime', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('datetime', '<=', $this->dateTo);
        }

        // ── Search (name / email are encrypted → must decrypt in PHP) ─────────
        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            // Step 1: pull candidate user_ids already narrowed by DB filters
            $candidateUserIds = (clone $query)
                ->pluck('user_id')
                ->unique()
                ->values();

            // Step 2: load users and search on decrypted name / email
            $matchedUserIds = User::with(['roles', 'applicant'])
                ->whereIn('id', $candidateUserIds)
                ->get()
                ->filter(function (User $user) use ($needle) {
                    $roleName = $user->roles->first()?->name ?? '';

                    // Applicants: name lives on the applicant relation (also encrypted)
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

                    // All users: decrypted name + email via Encrypted cast
                    $name  = strtolower($user->name  ?? '');
                    $email = strtolower($user->email ?? '');

                    return str_contains($name, $needle)
                        || str_contains($email, $needle);
                })
                ->pluck('id');

            // Step 3: also match datetime string so users can search
            // e.g. "Mar 27", "2025", "10:30 AM"
            $datetimeMatchedLogIds = (clone $query)
                ->get(['id', 'datetime'])
                ->filter(fn($log) => str_contains(
                    strtolower($log->datetime->format('M d, Y h:i:s A')),
                    $needle
                ))
                ->pluck('id');

            // Step 4: combine both sets
            $query->where(function ($q) use ($matchedUserIds, $datetimeMatchedLogIds) {
                $q->whereIn('user_id', $matchedUserIds)
                  ->orWhereIn('id', $datetimeMatchedLogIds);
            });
        }

        $logs = $query->paginate($this->perPage);

        return view('livewire.admin.account-logs', [
            'logs' => $logs,
        ]);
    }
}