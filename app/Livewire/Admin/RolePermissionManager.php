<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionManager extends Component
{
    use WithPagination;

    // -------------------------------------------------------
    // TAB STATE
    // -------------------------------------------------------
    public string $activeTab = 'roles';

    // -------------------------------------------------------
    // SEARCH
    // -------------------------------------------------------
    public string $roleSearch = '';
    public string $permissionSearch = '';

    // -------------------------------------------------------
    // PER PAGE
    // -------------------------------------------------------
    public int $rolePerPage = 10;
    public int $permissionPerPage = 10;
    public array $perPageOptions = [10, 25, 50, 100];

    // -------------------------------------------------------
    // MODAL STATE
    // -------------------------------------------------------
    public bool $showModal = false;
    public string $modalMode = 'create';   // create | edit
    public string $modalTarget = 'role';   // role | permission

    // -------------------------------------------------------
    // ROLE FORM
    // -------------------------------------------------------
    public string $roleName = '';
    public array $rolePermissions = [];    // selected permission IDs (as strings)
    public ?int $editRoleId = null;

    // Permission search INSIDE the modal
    public string $modalPermissionSearch = '';

    // -------------------------------------------------------
    // PERMISSION FORM
    // -------------------------------------------------------
    public string $permissionName = '';
    public ?int $editPermissionId = null;

    // -------------------------------------------------------
    // DELETE CONFIRMATION
    // -------------------------------------------------------
    public bool $showDeleteConfirm = false;
    public ?int $deleteId = null;
    public string $deleteTarget = 'role';

    // -------------------------------------------------------
    // SYSTEM ROLES — protected from edit/delete
    // -------------------------------------------------------
    protected array $systemRoles = [
        'admin',
        'super-admin',
        'applicant',
        'panel',
        'nbc',
    ];

    // -------------------------------------------------------
    // RESET PAGINATION ON SEARCH / PER-PAGE CHANGE
    // -------------------------------------------------------
    public function updatingRoleSearch(): void
    {
        $this->resetPage('roles');
    }

    public function updatingPermissionSearch(): void
    {
        $this->resetPage('permissions');
    }

    public function updatingRolePerPage(): void
    {
        $this->resetPage('roles');
    }

    public function updatingPermissionPerPage(): void
    {
        $this->resetPage('permissions');
    }

    // -------------------------------------------------------
    // TAB
    // -------------------------------------------------------
    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->closeModal();
    }

    // -------------------------------------------------------
    // MODAL HELPERS
    // -------------------------------------------------------
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->roleName = '';
        $this->rolePermissions = [];
        $this->editRoleId = null;
        $this->permissionName = '';
        $this->editPermissionId = null;
        $this->modalPermissionSearch = '';
    }

    // -------------------------------------------------------
    // SELECT ALL / DESELECT ALL  (only affects FILTERED set)
    // -------------------------------------------------------
    public function selectAllPermissions(): void
    {
        $filtered = $this->getFilteredPermissionIds();
        $this->rolePermissions = array_values(
            array_unique(
                array_merge($this->rolePermissions, $filtered)
            )
        );
    }

    public function deselectAllPermissions(): void
    {
        $filtered = $this->getFilteredPermissionIds();
        $this->rolePermissions = array_values(
            array_diff($this->rolePermissions, $filtered)
        );
    }

    /**
     * Returns string IDs of permissions matching the current modalPermissionSearch.
     */
    private function getFilteredPermissionIds(): array
    {
        $query = Permission::orderBy('name');

        if ($this->modalPermissionSearch !== '') {
            $query->where('name', 'like', '%' . $this->modalPermissionSearch . '%');
        }

        return $query->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    /**
     * Whether every permission in the currently filtered list is already selected.
     */
    public function areAllFilteredSelected(): bool
    {
        $filtered = $this->getFilteredPermissionIds();
        if (empty($filtered)) {
            return false;
        }
        return empty(array_diff($filtered, $this->rolePermissions));
    }

    // -------------------------------------------------------
    // ROLE CRUD
    // -------------------------------------------------------
    public function openCreateRole(): void
    {
        $this->resetForm();
        $this->modalTarget = 'role';
        $this->modalMode = 'create';
        $this->showModal = true;
    }

    public function openEditRole(int $roleId): void
    {
        $role = Role::find($roleId);
        if (!$role || in_array($role->name, $this->systemRoles)) {
            return;
        }

        $this->editRoleId = $role->id;
        $this->roleName = $role->name;
        $this->rolePermissions = $role->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->modalTarget = 'role';
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function saveRole(): void
    {
        $this->validate([
            'roleName' => 'required|string|max:255',
        ]);

        $name = strtolower(str_replace(' ', '-', trim($this->roleName)));

        if ($this->modalMode === 'create') {
            if (Role::where('name', $name)->exists()) {
                session()->flash('error', "Role '{$name}' already exists.");
                return;
            }

            $role = Role::create(['name' => $name]);
            $role->syncPermissions($this->rolePermissions);

            session()->flash('success', "Role '{$name}' created successfully.");
        } else {
            $role = Role::find($this->editRoleId);
            if (!$role || in_array($role->name, $this->systemRoles)) {
                return;
            }

            if (Role::where('name', $name)->where('id', '!=', $role->id)->exists()) {
                session()->flash('error', "Role '{$name}' already exists.");
                return;
            }

            $role->update(['name' => $name]);
            $role->syncPermissions($this->rolePermissions);

            session()->flash('success', "Role '{$name}' updated successfully.");
        }

        $this->closeModal();
    }

    public function confirmDeleteRole(int $roleId): void
    {
        $role = Role::find($roleId);
        if (!$role || in_array($role->name, $this->systemRoles)) {
            return;
        }

        $this->deleteId = $roleId;
        $this->deleteTarget = 'role';
        $this->showDeleteConfirm = true;
    }

    public function deleteRole(): void
    {
        $role = Role::find($this->deleteId);
        if ($role && !in_array($role->name, $this->systemRoles)) {
            $roleName = $role->name;
            $role->delete();
            session()->flash('success', "Role '{$roleName}' deleted successfully.");
        }

        $this->cancelDelete();
    }

    // -------------------------------------------------------
    // PERMISSION CRUD
    // -------------------------------------------------------
    public function openCreatePermission(): void
    {
        $this->resetForm();
        $this->modalTarget = 'permission';
        $this->modalMode = 'create';
        $this->showModal = true;
    }

    public function openEditPermission(int $permissionId): void
    {
        $permission = Permission::find($permissionId);
        if (!$permission) {
            return;
        }

        $this->editPermissionId = $permission->id;
        $this->permissionName = $permission->name;
        $this->modalTarget = 'permission';
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function savePermission(): void
    {
        $this->validate([
            'permissionName' => 'required|string|max:255',
        ]);

        $name = strtolower(str_replace(' ', '-', trim($this->permissionName)));

        if ($this->modalMode === 'create') {
            if (Permission::where('name', $name)->exists()) {
                session()->flash('error', "Permission '{$name}' already exists.");
                return;
            }

            Permission::create(['name' => $name]);
            session()->flash('success', "Permission '{$name}' created successfully.");
        } else {
            $permission = Permission::find($this->editPermissionId);
            if (!$permission) {
                return;
            }

            if (Permission::where('name', $name)->where('id', '!=', $permission->id)->exists()) {
                session()->flash('error', "Permission '{$name}' already exists.");
                return;
            }

            $permission->update(['name' => $name]);
            session()->flash('success', "Permission '{$name}' updated successfully.");
        }

        $this->closeModal();
    }

    public function confirmDeletePermission(int $permissionId): void
    {
        $this->deleteId = $permissionId;
        $this->deleteTarget = 'permission';
        $this->showDeleteConfirm = true;
    }

    public function deletePermission(): void
    {
        $permission = Permission::find($this->deleteId);
        if ($permission) {
            $permName = $permission->name;
            $permission->delete();
            session()->flash('success', "Permission '{$permName}' deleted successfully.");
        }

        $this->cancelDelete();
    }

    // -------------------------------------------------------
    // DELETE CANCEL
    // -------------------------------------------------------
    public function cancelDelete(): void
    {
        $this->showDeleteConfirm = false;
        $this->deleteId = null;
        $this->deleteTarget = 'role';
    }

    // -------------------------------------------------------
    // HELPER
    // -------------------------------------------------------
    public function isSystemRole(string $name): bool
    {
        return in_array($name, $this->systemRoles);
    }

    // -------------------------------------------------------
    // RENDER
    // -------------------------------------------------------
    public function render()
    {
        $roles = Role::when($this->roleSearch, function ($query) {
                $query->where('name', 'like', '%' . $this->roleSearch . '%');
            })
            ->withCount('permissions')
            ->paginate($this->rolePerPage, page: $this->getPage('roles'), pageName: 'roles');

        $permissions = Permission::when($this->permissionSearch, function ($query) {
                $query->where('name', 'like', '%' . $this->permissionSearch . '%');
            })
            ->withCount('roles')
            ->paginate($this->permissionPerPage, page: $this->getPage('permissions'), pageName: 'permissions');

        // Permissions filtered by modal search (for the role create/edit modal)
        $allPermissions = Permission::when($this->modalPermissionSearch, function ($query) {
                $query->where('name', 'like', '%' . $this->modalPermissionSearch . '%');
            })
            ->orderBy('name')
            ->get();

        $allFilteredSelected = $this->areAllFilteredSelected();

        return view('livewire.admin.role-permission-manager', [
            'roles'               => $roles,
            'permissions'         => $permissions,
            'allPermissions'      => $allPermissions,
            'allFilteredSelected' => $allFilteredSelected,
        ]);
    }
}