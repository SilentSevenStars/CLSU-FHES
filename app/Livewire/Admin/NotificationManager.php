<?php

namespace App\Livewire\Admin;

use App\Models\Applicant;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, hired, not_hired
    public $selectedApplicants = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedApplicants = $this->getApplicants()->pluck('id')->toArray();
        } else {
            $this->selectedApplicants = [];
        }
    }

    public function getApplicants()
    {
        $query = Applicant::with(['user', 'notifications'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('middle_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                if ($this->statusFilter === 'hired') {
                    $q->where('hired', true);
                } elseif ($this->statusFilter === 'not_hired') {
                    $q->where('hired', false);
                }
            });

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function sendMessage()
    {
        if (empty($this->selectedApplicants)) {
            session()->flash('error', 'Please select at least one applicant.');
            return;
        }

        return redirect()->route('admin.message', ['applicants' => implode(',', $this->selectedApplicants)]);
    }

    public function render()
    {
        return view('livewire.admin.notification-manager', [
            'applicants' => $this->getApplicants(),
            'totalSelected' => count($this->selectedApplicants),
        ]);
    }
}