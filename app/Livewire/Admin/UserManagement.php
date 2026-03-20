<?php

namespace App\Livewire\Admin;

use App\Models\Applicant;
use App\Models\College;
use App\Models\Department;
use App\Models\NbcCommittee;
use App\Models\Panel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;

    // Common properties
    public $user_id;
    public $email;
    public $password;
    public $password_confirmation;
    public $isEditMode = false;
    public $showModal = false;
    public $showArchiveModal = false;
    public $archiveUserId;
    public $perPage = 10;
    public $search = '';
    public $filterRole = 'all';

    // Regular user properties
    public $name;
    public $role;

    // Applicant properties
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;

    // Panel properties
    public $panel_position;
    public $college_id;
    public $department_id;

    // NBC Committee properties
    public $nbc_position;

    protected $paginationTheme = 'tailwind';

    /**
     * Positions that do NOT require a college or department.
     */
    private function isNoCollegeDeptPosition(): bool
    {
        return in_array($this->panel_position, [
            'chair_fsb',
            'fai_president',
            'clutches_president',
            'director_hr',
        ]);
    }

    /**
     * Positions that require college but NOT department.
     */
    private function isNoDeptPosition(): bool
    {
        return in_array($this->panel_position, [
            'dean',
            'chair_fsb',
            'fai_president',
            'clutches_president',
            'director_hr',
        ]);
    }

    protected function rules()
    {
        $rules = [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user_id),
            ],
        ];

        // Password rules
        if ($this->isEditMode) {
            $rules['password'] = 'nullable|min:8|confirmed';
        } else {
            if ($this->filterRole !== 'applicant') {
                $rules['password'] = 'required|min:8|confirmed';
            }
        }

        // Role-specific validation
        switch ($this->filterRole) {
            case 'applicant':
                $rules['first_name'] = 'required|string|max:255';
                $rules['last_name']  = 'required|string|max:255';
                $rules['middle_name'] = 'nullable|string|max:255';
                $rules['suffix']     = 'nullable|string|max:50';
                break;

            case 'panel':
                $rules['name']           = 'required|string|max:255';
                $rules['panel_position'] = 'required|in:head,señior,dean,chair_fsb,fai_president,clutches_president,director_hr';

                $rules['college_id'] = [
                    Rule::requiredIf(fn() => !$this->isNoCollegeDeptPosition()),
                    'nullable',
                    'exists:colleges,id',
                ];

                $rules['department_id'] = [
                    Rule::requiredIf(fn() => !$this->isNoDeptPosition()),
                    'nullable',
                    'exists:departments,id',
                ];
                break;

            case 'nbc':
                $rules['name']         = 'required|string|max:255';
                $rules['nbc_position'] = [
                    'required',
                    Rule::in(NbcCommittee::validPositions()),
                ];
                break;

            default:
                $rules['name'] = 'required|string|max:255';
                $rules['role'] = [
                    'required',
                    Rule::in(Role::whereNotIn('name', ['applicant', 'nbc', 'panel'])
                        ->pluck('name')
                        ->toArray())
                ];
                break;
        }

        return $rules;
    }

    public function updatingSearch()   { $this->resetPage(); }
    public function updatingPerPage()  { $this->resetPage(); }
    public function updatingFilterRole() { $this->resetPage(); }

    public function updatedCollegeId()
    {
        $this->department_id = null;
    }

    public function updatedPanelPosition()
    {
        if ($this->isNoCollegeDeptPosition()) {
            $this->college_id    = null;
            $this->department_id = null;
        }
        if ($this->isNoDeptPosition()) {
            $this->department_id = null;
        }
    }

    public function openCreateModal($userType = 'regular')
    {
        $this->resetForm();
        $this->filterRole = $userType;
        $this->isEditMode = false;
        $this->showModal  = true;
    }

    public function openEditModal($id, $userType)
    {
        $this->resetForm();
        $this->filterRole = $userType;
        $user = User::with('roles')->findOrFail($id);

        $this->user_id = $user->id;
        $this->email   = $user->email;

        switch ($userType) {
            case 'applicant':
                $applicant = Applicant::where('user_id', $user->id)->first();
                if ($applicant) {
                    $this->first_name  = $applicant->first_name;
                    $this->middle_name = $applicant->middle_name;
                    $this->last_name   = $applicant->last_name;
                    $this->suffix      = $applicant->suffix;
                }
                break;

            case 'panel':
                $this->name = $user->name;
                $panel = Panel::where('user_id', $user->id)->first();
                if ($panel) {
                    $this->panel_position = $panel->panel_position;
                    $this->college_id     = $panel->college_id;
                    $this->department_id  = $panel->department_id;
                }
                break;

            case 'nbc':
                $this->name = $user->name;
                $nbcCommittee = NbcCommittee::where('user_id', $user->id)->first();
                if ($nbcCommittee) {
                    // position is decrypted automatically by the Encrypted cast
                    $this->nbc_position = $nbcCommittee->position;
                }
                break;

            default:
                $this->name = $user->name;
                $this->role = $user->roles->first()?->name ?? '';
                break;
        }

        $this->isEditMode = true;
        $this->showModal  = true;
    }

    public function openArchiveModal($id)
    {
        $this->archiveUserId  = $id;
        $this->showArchiveModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeArchiveModal()
    {
        $this->showArchiveModal = false;
        $this->archiveUserId   = null;
    }

    public function resetForm()
    {
        $this->user_id              = null;
        $this->name                 = '';
        $this->first_name           = '';
        $this->middle_name          = '';
        $this->last_name            = '';
        $this->suffix               = '';
        $this->email                = '';
        $this->password             = '';
        $this->password_confirmation = '';
        $this->role                 = '';
        $this->panel_position       = '';
        $this->college_id           = '';
        $this->department_id        = '';
        $this->nbc_position         = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        // ── Chairperson uniqueness guard ──────────────────────────────────────
        if ($this->filterRole === 'nbc' &&
            $this->nbc_position === NbcCommittee::POSITION_CHAIRPERSON)
        {
            $excludeUserId = $this->isEditMode ? $this->user_id : null;

            if (NbcCommittee::chairpersonExists($excludeUserId)) {
                $this->addError(
                    'nbc_position',
                    'A CLSU NBC 461 Chairperson already exists. Only one Chairperson is allowed.'
                );
                return;
            }
        }

        try {
            if ($this->isEditMode) {
                $this->updateUser();
            } else {
                $this->createUser();
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function createUser()
    {
        $userData = [
            'email'              => $this->email,
            'email_verified_at'  => now(),
        ];

        if ($this->password) {
            $userData['password'] = bcrypt($this->password);
        }

        if (in_array($this->filterRole, ['panel', 'nbc']) ||
            !in_array($this->filterRole, ['applicant', 'panel', 'nbc'])) {
            $userData['name'] = $this->name;
        }

        $user = User::create($userData);

        switch ($this->filterRole) {
            case 'applicant':
                $user->assignRole('applicant');
                Applicant::create([
                    'user_id'     => $user->id,
                    'first_name'  => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name'   => $this->last_name,
                    'suffix'      => $this->suffix,
                ]);
                break;

            case 'panel':
                $user->assignRole('panel');
                Panel::create([
                    'user_id'        => $user->id,
                    'panel_position' => $this->panel_position,
                    'college_id'     => $this->isNoCollegeDeptPosition() ? null : $this->college_id,
                    'department_id'  => $this->isNoDeptPosition()        ? null : $this->department_id,
                ]);
                break;

            case 'nbc':
                $user->assignRole('nbc');
                // position is encrypted automatically by the Encrypted cast
                NbcCommittee::create([
                    'user_id'  => $user->id,
                    'position' => $this->nbc_position,
                ]);
                break;

            default:
                $user->assignRole($this->role);
                break;
        }

        session()->flash('success', ucfirst($this->filterRole) . ' user created successfully!');
    }

    private function updateUser()
    {
        $user = User::findOrFail($this->user_id);

        $userData = [
            'email'             => $this->email,
            'email_verified_at' => Carbon::now(),
        ];

        if ($this->password) {
            $userData['password'] = bcrypt($this->password);
        }

        if (in_array($this->filterRole, ['panel', 'nbc']) ||
            !in_array($this->filterRole, ['applicant', 'panel', 'nbc'])) {
            $userData['name'] = $this->name;
        }

        $user->update($userData);

        switch ($this->filterRole) {
            case 'applicant':
                $applicant = Applicant::where('user_id', $user->id)->first();
                if ($applicant) {
                    $applicant->update([
                        'first_name'  => $this->first_name,
                        'middle_name' => $this->middle_name,
                        'last_name'   => $this->last_name,
                        'suffix'      => $this->suffix,
                    ]);
                }
                break;

            case 'panel':
                $panelData = [
                    'panel_position' => $this->panel_position,
                    'college_id'     => $this->isNoCollegeDeptPosition() ? null : $this->college_id,
                    'department_id'  => $this->isNoDeptPosition()        ? null : $this->department_id,
                ];
                $panel = Panel::where('user_id', $user->id)->first();
                if ($panel) {
                    $panel->update($panelData);
                } else {
                    Panel::create(array_merge($panelData, ['user_id' => $user->id]));
                }
                break;

            case 'nbc':
                $nbcCommittee = NbcCommittee::where('user_id', $user->id)->first();
                if ($nbcCommittee) {
                    // position encrypted automatically by the Encrypted cast
                    $nbcCommittee->update(['position' => $this->nbc_position]);
                }
                break;

            default:
                $user->syncRoles([$this->role]);
                break;
        }

        session()->flash('success', ucfirst($this->filterRole) . ' user updated successfully!');
    }

    public function archive()
    {
        try {
            $user = User::findOrFail($this->archiveUserId);

            if ($user->id === Auth::id()) {
                session()->flash('error', 'You cannot archive your own account!');
                $this->closeArchiveModal();
                return;
            }

            $user->update(['archive' => true]);
            session()->flash('success', 'User archived successfully!');
            $this->closeArchiveModal();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = User::with(['roles', 'panel.college', 'panel.department', 'nbcCommittee', 'applicant'])
            ->where('id', '!=', Auth::id())
            ->where('archive', false);

        if ($this->filterRole !== 'all') {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->filterRole);
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('applicant', function ($subQ) {
                        $subQ->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        $baseQuery    = User::where('id', '!=', Auth::id())->where('archive', false);
        $totalUsers   = $baseQuery->count();
        $adminCount   = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'admin'))->count();
        $superAdminCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->count();
        $panelCount   = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'panel'))->count();
        $nbcCount     = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'nbc'))->count();
        $applicantCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'applicant'))->count();

        $availableRoles = Role::whereNotIn('name', ['applicant', 'nbc', 'panel'])
            ->orderBy('name')
            ->get();

        $colleges = College::orderBy('name')->get();

        $departments = Department::query()
            ->when($this->college_id, fn($q) => $q->where('college_id', $this->college_id))
            ->orderBy('name')
            ->get();

        $chairpersonTaken = NbcCommittee::chairpersonExists(
            $this->isEditMode ? $this->user_id : null
        );

        return view('livewire.admin.user-management', [
            'users'            => $users,
            'availableRoles'   => $availableRoles,
            'colleges'         => $colleges,
            'departments'      => $departments,
            'totalUsers'       => $totalUsers,
            'adminCount'       => $adminCount,
            'superAdminCount'  => $superAdminCount,
            'panelCount'       => $panelCount,
            'nbcCount'         => $nbcCount,
            'applicantCount'   => $applicantCount,
            'chairpersonTaken' => $chairpersonTaken,
        ]);
    }
}