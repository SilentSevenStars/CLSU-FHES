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
            // Only require password for non-applicants during creation
            if ($this->filterRole !== 'applicant') {
                $rules['password'] = 'required|min:8|confirmed';
            }
        }

        // Role-specific validation
        switch ($this->filterRole) {
            case 'applicant':
                $rules['first_name'] = 'required|string|max:255';
                $rules['last_name'] = 'required|string|max:255';
                $rules['middle_name'] = 'nullable|string|max:255';
                $rules['suffix'] = 'nullable|string|max:50';
                break;

            case 'panel':
                $rules['name'] = 'required|string|max:255';
                $rules['panel_position'] = 'required|in:head,señior,dean';
                $rules['college_id'] = 'required|exists:colleges,id';
                $rules['department_id'] = [
                    Rule::requiredIf(fn() => $this->panel_position !== 'dean'),
                    'nullable',
                    'exists:departments,id'
                ];
                break;

            case 'nbc':
                $rules['name'] = 'required|string|max:255';
                $rules['nbc_position'] = 'required|in:evaluator,verifier';
                break;

            default:
                // Regular users (admin, super-admin, etc.)
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function openCreateModal($userType = 'regular')
    {
        $this->resetForm();
        $this->filterRole = $userType;
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id, $userType)
    {
        $this->resetForm();
        $this->filterRole = $userType;
        $user = User::with('roles')->findOrFail($id);

        $this->user_id = $user->id;
        $this->email = $user->email;

        switch ($userType) {
            case 'applicant':
                $applicant = Applicant::where('user_id', $user->id)->first();
                if ($applicant) {
                    $this->first_name = $applicant->first_name;
                    $this->middle_name = $applicant->middle_name;
                    $this->last_name = $applicant->last_name;
                    $this->suffix = $applicant->suffix;
                }
                break;

            case 'panel':
                $this->name = $user->name;
                $panel = Panel::where('user_id', $user->id)->first();
                if ($panel) {
                    $this->panel_position = $panel->panel_position;
                    $this->college_id = $panel->college_id;
                    $this->department_id = $panel->department_id;
                }
                break;

            case 'nbc':
                $this->name = $user->name;
                $nbcCommittee = NbcCommittee::where('user_id', $user->id)->first();
                if ($nbcCommittee) {
                    $this->nbc_position = $nbcCommittee->position;
                }
                break;

            default:
                $this->name = $user->name;
                $this->role = $user->roles->first()?->name ?? '';
                break;
        }

        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function openArchiveModal($id)
    {
        $this->archiveUserId = $id;
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
        $this->archiveUserId = null;
    }

    public function resetForm()
    {
        $this->user_id = null;
        $this->name = '';
        $this->first_name = '';
        $this->middle_name = '';
        $this->last_name = '';
        $this->suffix = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->panel_position = '';
        $this->college_id = '';
        $this->department_id = '';
        $this->nbc_position = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

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
            'email' => $this->email,
            'email_verified_at' => now(),
        ];

        // Add password if provided
        if ($this->password) {
            $userData['password'] = $this->password;
        }

        // Regular users and panel/nbc need name field
        if (in_array($this->filterRole, ['panel', 'nbc']) || !in_array($this->filterRole, ['applicant', 'panel', 'nbc'])) {
            $userData['name'] = $this->name;
        }

        $user = User::create($userData);

        // Assign role and create related records
        switch ($this->filterRole) {
            case 'applicant':
                $user->assignRole('applicant');
                
                // Create applicant record
                Applicant::create([
                    'user_id' => $user->id,
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                ]);
                break;

            case 'panel':
                $user->assignRole('panel');
                Panel::create([
                    'user_id' => $user->id,
                    'panel_position' => $this->panel_position,
                    'college_id' => $this->college_id,
                    'department_id' => $this->panel_position === 'dean' ? null : $this->department_id,
                ]);
                break;

            case 'nbc':
                $user->assignRole('nbc');
                NbcCommittee::create([
                    'user_id' => $user->id,
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
            'email' => $this->email,
            'email_verified_at' => Carbon::now()
        ];

        // Update password if provided
        if ($this->password) {
            $userData['password'] = $this->password;
        }

        // Regular users and panel/nbc need name field
        if (in_array($this->filterRole, ['panel', 'nbc']) || !in_array($this->filterRole, ['applicant', 'panel', 'nbc'])) {
            $userData['name'] = $this->name;
        }

        $user->update($userData);

        // Update role-specific data
        switch ($this->filterRole) {
            case 'applicant':
                $applicant = Applicant::where('user_id', $user->id)->first();
                if ($applicant) {
                    $applicant->update([
                        'first_name' => $this->first_name,
                        'middle_name' => $this->middle_name,
                        'last_name' => $this->last_name,
                        'suffix' => $this->suffix,
                    ]);
                }
                break;

            case 'panel':
                $panel = Panel::where('user_id', $user->id)->first();
                if ($panel) {
                    $panel->update([
                        'panel_position' => $this->panel_position,
                        'college_id' => $this->college_id,
                        'department_id' => $this->panel_position === 'dean' ? null : $this->department_id,
                    ]);
                }
                break;

            case 'nbc':
                $nbcCommittee = NbcCommittee::where('user_id', $user->id)->first();
                if ($nbcCommittee) {
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

        // Filter by role
        if ($this->filterRole !== 'all') {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->filterRole);
            });
        }

        // Search functionality
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

        // Get statistics counts
        $baseQuery = User::where('id', '!=', Auth::id())->where('archive', false);
        
        $totalUsers = $baseQuery->count();
        $adminCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'admin'))->count();
        $superAdminCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->count();
        $panelCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'panel'))->count();
        $nbcCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'nbc'))->count();
        $applicantCount = (clone $baseQuery)->whereHas('roles', fn($q) => $q->where('name', 'applicant'))->count();

        // Get data for dropdowns
        $availableRoles = Role::whereNotIn('name', ['applicant', 'nbc', 'panel'])
            ->orderBy('name')
            ->get();
        
        $colleges = College::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'availableRoles' => $availableRoles,
            'colleges' => $colleges,
            'departments' => $departments,
            'totalUsers' => $totalUsers,
            'adminCount' => $adminCount,
            'superAdminCount' => $superAdminCount,
            'panelCount' => $panelCount,
            'nbcCount' => $nbcCount,
            'applicantCount' => $applicantCount,
        ]);
    }
}