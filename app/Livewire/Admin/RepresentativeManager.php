<?php

namespace App\Livewire\Admin;

use App\Models\Representative;
use App\Services\AccountActivityService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class RepresentativeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public $name, $position;
    public $representativeId;

    // Predefined positions for the footer
    public $availablePositions = [
        'Member, Supervising Admin. Officer, HRMO',
        'Member, FAI President/Representative',
        'Member, GLUTCHES President',
        'Member, Ranking Faculty',
        'Dean, CASS',
        'Dean, CEN Representative',
        'Dean, COS',
        'Dean, CED',
        'Dean, CF',
        'Dean, CBA',
        'Senior Faculty',
        'Head, Dept DABE, Representative',
        'Head, Dept Business',
        'Head, ISPELS',
        'Chairman, Faculty Selection Board & VPAA',
        'University President',
    ];

    public $showCreateModal = false;
    public $showEditModal   = false;

    // Shown when a taken position is selected in the create modal
    public $takenByName   = null;
    public $takenById     = null;
    public $positionTaken = false;

    // Snapshot of original values for diffing on update
    public string $oldName     = '';
    public string $oldPosition = '';

    protected $rules = [
        'name'     => 'required|string|max:255',
        'position' => 'required|string|max:255',
    ];

    protected $listeners = ['deleteConfirmed'];

    // -------------------------------------------------------------------------
    // Create modal
    // -------------------------------------------------------------------------

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    /**
     * Fires whenever the position dropdown changes in the create modal.
     * Checks if the chosen position is already occupied and surfaces the
     * existing representative so the admin knows to edit instead of create.
     */
    public function updatedPosition($value)
    {
        // Only run this check while the create modal is open
        if (!$this->showCreateModal) {
            return;
        }

        $this->positionTaken = false;
        $this->takenByName   = null;
        $this->takenById     = null;

        if (!$value) {
            return;
        }

        $existing = Representative::where('position', $value)->first();

        if ($existing) {
            $this->positionTaken = true;
            $this->takenByName   = $existing->name;
            $this->takenById     = $existing->id;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function store()
    {
        $this->validate();

        // Guard: re-check at the time of submission in case the user bypassed the UI warning
        $existing = Representative::where('position', $this->position)->first();

        if ($existing) {
            $this->addError('position', "This position is already assigned to \"{$existing->name}\". Please edit that representative instead.");
            $this->positionTaken = true;
            $this->takenByName   = $existing->name;
            $this->takenById     = $existing->id;
            return;
        }

        Representative::create([
            'name'     => $this->name,
            'position' => $this->position,
        ]);

        // ── Activity log ──────────────────────────────────────────────────────
        AccountActivityService::log(
            Auth::user(),
            "Created a new representative \"{$this->name}\" — Position: \"{$this->position}\"."
        );
        // ─────────────────────────────────────────────────────────────────────

        $this->closeCreateModal();

        $this->js("
            window.dispatchEvent(new CustomEvent('show-alert', {
                detail: { type: 'success', message: 'Representative created successfully!' }
            }));
        ");
    }

    /**
     * Called from the "Edit instead" button in the create modal.
     * Closes the create modal and opens the edit modal for the taken representative.
     */
    public function switchToEdit()
    {
        $id = $this->takenById;
        $this->closeCreateModal();
        $this->openEditModal($id);
    }

    // -------------------------------------------------------------------------
    // Edit modal
    // -------------------------------------------------------------------------

    public function openEditModal($id)
    {
        $representative = Representative::findOrFail($id);

        $this->representativeId = $id;
        $this->name             = $representative->name;
        $this->position         = $representative->position;

        // Store originals for diff logging
        $this->oldName     = $representative->name;
        $this->oldPosition = $representative->position;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        $representative = Representative::findOrFail($this->representativeId);

        $representative->update([
            'name'     => $this->name,
            'position' => $this->position,
        ]);

        // ── Activity log — only record what actually changed ──────────────────
        $changes = [];

        if ($this->oldName !== $this->name)
            $changes[] = "name: \"{$this->oldName}\" → \"{$this->name}\"";

        if ($this->oldPosition !== $this->position)
            $changes[] = "position: \"{$this->oldPosition}\" → \"{$this->position}\"";

        if (!empty($changes)) {
            AccountActivityService::log(
                Auth::user(),
                "Updated representative \"{$this->name}\" (ID: {$this->representativeId}) — "
                    . implode('; ', $changes) . '.'
            );
        }
        // ─────────────────────────────────────────────────────────────────────

        $this->closeEditModal();

        $this->js("
            window.dispatchEvent(new CustomEvent('show-alert', {
                detail: { type: 'success', message: 'Representative updated successfully!' }
            }));
        ");
    }

    // -------------------------------------------------------------------------
    // Delete
    // -------------------------------------------------------------------------

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', id: $id);
    }

    public function deleteConfirmed($id)
    {
        $representative = Representative::findOrFail($id);

        // Capture details before the record is destroyed
        $deletedName     = $representative->name;
        $deletedPosition = $representative->position;

        $representative->delete();

        // ── Activity log ──────────────────────────────────────────────────────
        AccountActivityService::log(
            Auth::user(),
            "Deleted representative \"{$deletedName}\" — Position: \"{$deletedPosition}\"."
        );
        // ─────────────────────────────────────────────────────────────────────

        $this->js("
            window.dispatchEvent(new CustomEvent('show-alert', {
                detail: { type: 'success', message: 'Representative deleted successfully!' }
            }));
        ");
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function resetForm()
    {
        $this->reset([
            'name',
            'position',
            'representativeId',
            'oldName',
            'oldPosition',
            'positionTaken',
            'takenByName',
            'takenById',
        ]);
        $this->resetValidation();
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render()
    {
        // Pull all records first so Eloquent's Encrypted cast can decrypt them.
        // SQL LIKE on ciphertext is meaningless — every encrypted value is
        // unique binary data regardless of the original plaintext — so the
        // filter must happen in PHP after decryption.
        if (!$this->search) {
            // No search: let the DB paginate for performance
            $representatives = Representative::orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        } else {
            $search = strtolower($this->search);

            $all = Representative::orderBy('created_at', 'desc')
                ->get()
                ->filter(function ($rep) use ($search) {
                    // name and position are auto-decrypted by the Encrypted cast
                    $name     = strtolower($rep->name     ?? '');
                    $position = strtolower($rep->position ?? '');

                    return str_contains($name, $search)
                        || str_contains($position, $search);
                })
                ->values();

            // Manual pagination over the filtered in-memory collection
            $perPage     = (int) $this->perPage;
            $currentPage = $this->getPage();

            $representatives = new LengthAwarePaginator(
                $all->forPage($currentPage, $perPage),
                $all->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return view('livewire.admin.representative-manager', [
            'representatives' => $representatives,
        ]);
    }
}