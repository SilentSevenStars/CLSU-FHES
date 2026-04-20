<?php

namespace App\Livewire\Applicant;

use App\Models\JobApplication;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApplyJob extends Component
{
    public $positions;
    public $paginatedPositions;
    public $applied = [];
    public $search = '';
    public $selectedPosition = null;
    public $showModal = false;
    public $viewType = 'card';
    public $perPage = 10;
    public $currentPage = 1;
    public $totalPages = 1;
    public $totalCount = 0;
    public $fontSize = 'base';

    protected $listeners = ['job-application-submitted' => 'refreshAppliedPositions'];

    public function mount()
    {
        $this->loadPositions();
    }

    public function updatedSearch()
    {
        $this->currentPage = 1;
        $this->loadPositions();
    }

    public function updatedPerPage()
    {
        $this->currentPage = 1;
        $this->paginatePositions();
    }

    public function loadPositions()
    {
        $today = Carbon::today();

        $rankOrder = [
            'College/University Professor',
            'Professor VI',
            'Professor V',
            'Professor IV',
            'Professor III',
            'Professor II',
            'Professor I',
            'Associate Professor V',
            'Associate Professor IV',
            'Associate Professor III',
            'Associate Professor II',
            'Associate Professor I',
            'Assistant Professor IV',
            'Assistant Professor III',
            'Assistant Professor II',
            'Assistant Professor I',
            'Instructor III',
            'Instructor II',
            'Instructor I',
        ];

        $query = Position::with(['college', 'department'])
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('department', fn($dq) => $dq->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('college', fn($cq) => $cq->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhere('specialization', 'like', '%' . $this->search . '%');
            });
        }

        $rankList = implode("','", array_map(fn($r) => addslashes($r), $rankOrder));
        $query->orderByRaw("FIELD(name, '{$rankList}') = 0")
              ->orderByRaw("FIELD(name, '{$rankList}')")
              ->orderBy('college_id', 'asc')
              ->orderBy('department_id', 'asc');

        $this->positions = $query->get();
        $this->loadAppliedPositions();
        $this->paginatePositions();
    }

    public function paginatePositions()
    {
        $this->totalCount = $this->positions ? $this->positions->count() : 0;
        $this->totalPages = max(1, (int) ceil($this->totalCount / $this->perPage));

        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }

        $this->paginatedPositions = $this->positions
            ? $this->positions->slice(($this->currentPage - 1) * $this->perPage, $this->perPage)
            : collect();
    }

    public function loadAppliedPositions()
    {
        $user = Auth::user();
        if ($user && $user->applicant) {
            $this->applied = JobApplication::where('applicant_id', $user->applicant->id)
                ->where('archive', false)
                ->whereNotIn('status', ['hired', 'decline'])
                ->pluck('position_id')
                ->toArray();
        } else {
            $this->applied = [];
        }
    }

    public function setViewType($type)
    {
        $this->viewType = $type;
    }

    public function setFontSize($size)
    {
        $allowed = ['sm', 'base', 'lg', 'xl'];
        if (in_array($size, $allowed)) {
            $this->fontSize = $size;
        }
    }

    public function goToPage($page)
    {
        $page = (int) $page;
        if ($page >= 1 && $page <= $this->totalPages) {
            $this->currentPage = $page;
            $this->paginatePositions();
        }
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
            $this->paginatePositions();
        }
    }

    public function prevPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->paginatePositions();
        }
    }

    public function viewDetails($positionId)
    {
        $this->selectedPosition = Position::with(['college', 'department'])->find($positionId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedPosition = null;
    }

    public function refreshAppliedPositions()
    {
        $this->loadAppliedPositions();
        $this->loadPositions();
    }

    public function canEditApplication($positionId)
    {
        $user = Auth::user();
        if (!$user || !$user->applicant) return false;

        $application = JobApplication::where('applicant_id', $user->applicant->id)
            ->where('position_id', $positionId)
            ->whereNotIn('status', ['hired', 'decline'])
            ->where('archive', false)
            ->first();

        if (!$application) return false;

        $position = Position::find($positionId);
        $today = Carbon::today();

        return $position && $today->between(
            Carbon::parse($position->start_date),
            Carbon::parse($position->end_date)
        );
    }

    public function getApplicationId($positionId)
    {
        $user = Auth::user();
        if (!$user || !$user->applicant) return null;

        $application = JobApplication::where('applicant_id', $user->applicant->id)
            ->where('position_id', $positionId)
            ->whereNotIn('status', ['hired', 'decline'])
            ->where('archive', false)
            ->first();

        return $application ? $application->id : null;
    }

    public function numberToWords($number): string
    {
        $number = (int) $number;
        $words = [
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 21 => 'Twenty-One',
            22 => 'Twenty-Two', 23 => 'Twenty-Three', 24 => 'Twenty-Four',
            25 => 'Twenty-Five', 26 => 'Twenty-Six', 27 => 'Twenty-Seven',
            28 => 'Twenty-Eight', 29 => 'Twenty-Nine', 30 => 'Thirty',
            40 => 'Forty', 48 => 'Forty-Eight', 50 => 'Fifty',
            60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety',
            100 => 'One Hundred',
        ];

        if (isset($words[$number])) {
            return "{$words[$number]} ({$number})";
        }

        if ($number < 100) {
            $tens = (int) ($number / 10) * 10;
            $ones = $number % 10;
            if (isset($words[$tens]) && isset($words[$ones])) {
                return "{$words[$tens]}-{$words[$ones]} ({$number})";
            }
        }

        return "({$number})";
    }

    public function render()
    {
        $this->loadAppliedPositions();
        $hasActiveApplication = !empty($this->applied);

        return view('livewire.applicant.apply-job', [
            'hasActiveApplication' => $hasActiveApplication,
        ]);
    }
}