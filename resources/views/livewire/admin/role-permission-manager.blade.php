<div>
    {{-- ============================================================ --}}
    {{-- FLASH MESSAGES                                                 --}}
    {{-- ============================================================ --}}
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 flex items-center gap-3 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg"
             x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-sm font-medium">{{ session()->get('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 flex items-center gap-3 bg-red-600 text-white px-5 py-3 rounded-lg shadow-lg"
             x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="text-sm font-medium">{{ session()->get('error') }}</span>
        </div>
    @endif

    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">

            {{-- PAGE HEADER --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Roles & Permissions</h1>
                <p class="text-gray-500 text-sm mt-1">Manage application roles and their associated permissions.</p>
            </div>

            {{-- TABS --}}
            <div class="flex gap-1 bg-white rounded-xl border border-gray-200 p-1 w-fit mb-6 shadow-sm">
                <button wire:click="switchTab('roles')"
                    class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                           {{ $activeTab === 'roles' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    Roles
                    <span class="ml-2 text-xs opacity-70">({{ $roles->total() }})</span>
                </button>
                <button wire:click="switchTab('permissions')"
                    class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                           {{ $activeTab === 'permissions' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    Permissions
                    <span class="ml-2 text-xs opacity-70">({{ $permissions->total() }})</span>
                </button>
            </div>

            {{-- ============================================================ --}}
            {{-- ROLES TAB                                                      --}}
            {{-- ============================================================ --}}
            @if ($activeTab === 'roles')
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

                {{-- TOOLBAR --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-5 border-b border-gray-100">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        {{-- SEARCH --}}
                        <div class="relative flex-1 sm:flex-none sm:w-64">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input wire:model.live.debounce.400ms="roleSearch"
                                   type="text"
                                   placeholder="Search roles..."
                                   class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                        </div>

                        {{-- PER PAGE --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs text-gray-500">Show</span>
                            <select wire:model.live="rolePerPage"
                                    class="text-sm border border-gray-200 rounded-lg px-2.5 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent cursor-pointer">
                                @foreach ($perPageOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            <span class="text-xs text-gray-500">per page</span>
                        </div>
                    </div>

                    {{-- CREATE BUTTON --}}
                    <button wire:click="openCreateRole"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Role
                    </button>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role Name</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Permissions</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $index => $role)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-5 py-4 text-sm text-gray-500">{{ $roles->firstItem() + $index }}</td>
                                <td class="px-5 py-4">
                                    <span class="text-sm font-semibold text-gray-800">{{ $role->name }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-500">{{ $role->permissions_count }} permission{{ $role->permissions_count !== 1 ? 's' : '' }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($this->isSystemRole($role->name))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm6 4a1 1 0 11-2 0 1 1 0 012 0zm-6-4h12v2H5V9z" clip-rule="evenodd" />
                                            </svg>
                                            System
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                            Custom
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($this->isSystemRole($role->name))
                                            <span class="text-xs text-gray-400 italic">Protected</span>
                                        @else
                                            <button wire:click="openEditRole({{ $role->id }})"
                                                class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="confirmDeleteRole({{ $role->id }})"
                                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                            @if ($roles->isEmpty())
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">No roles found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION + INFO --}}
                @if ($roles->hasPages() || $roles->total() > 0)
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-100">
                    <span class="text-xs text-gray-500">
                        Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }} role{{ $roles->total() !== 1 ? 's' : '' }}
                    </span>
                    <div>
                        {{ $roles->links() }}
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- ============================================================ --}}
            {{-- PERMISSIONS TAB                                                --}}
            {{-- ============================================================ --}}
            @if ($activeTab === 'permissions')
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

                {{-- TOOLBAR --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-5 border-b border-gray-100">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        {{-- SEARCH --}}
                        <div class="relative flex-1 sm:flex-none sm:w-64">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input wire:model.live.debounce.400ms="permissionSearch"
                                   type="text"
                                   placeholder="Search permissions..."
                                   class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                        </div>

                        {{-- PER PAGE --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs text-gray-500">Show</span>
                            <select wire:model.live="permissionPerPage"
                                    class="text-sm border border-gray-200 rounded-lg px-2.5 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent cursor-pointer">
                                @foreach ($perPageOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            <span class="text-xs text-gray-500">per page</span>
                        </div>
                    </div>

                    {{-- CREATE BUTTON --}}
                    <button wire:click="openCreatePermission"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Permission
                    </button>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Permission Name</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Module</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $index => $permission)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-5 py-4 text-sm text-gray-500">{{ $permissions->firstItem() + $index }}</td>
                                <td class="px-5 py-4">
                                    <span class="text-sm font-semibold text-gray-800">{{ $permission->name }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $parts = explode('.', $permission->name);
                                        $module = $parts[0] ?? $permission->name;
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        {{ $module }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-500">{{ $permission->roles_count }} role{{ $permission->roles_count !== 1 ? 's' : '' }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="openEditPermission({{ $permission->id }})"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDeletePermission({{ $permission->id }})"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                            title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                            @if ($permissions->isEmpty())
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">No permissions found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION + INFO --}}
                @if ($permissions->hasPages() || $permissions->total() > 0)
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-100">
                    <span class="text-xs text-gray-500">
                        Showing {{ $permissions->firstItem() }} to {{ $permissions->lastItem() }} of {{ $permissions->total() }} permission{{ $permissions->total() !== 1 ? 's' : '' }}
                    </span>
                    <div>
                        {{ $permissions->links() }}
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- ============================================================ --}}
            {{-- CREATE / EDIT MODAL                                            --}}
            {{-- ============================================================ --}}
            @if ($showModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="fixed inset-0 bg-black bg-opacity-40" wire:click="closeModal"></div>

                <div class="relative bg-white rounded-2xl shadow-2xl w-full mx-4 overflow-hidden
                            {{ $modalTarget === 'role' ? 'max-w-xl' : 'max-w-lg' }}">

                    {{-- MODAL HEADER --}}
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50">
                        <div>
                            <h3 class="text-base font-bold text-gray-800">
                                {{ $modalMode === 'create' ? 'Create' : 'Edit' }} {{ ucfirst($modalTarget) }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $modalMode === 'create'
                                    ? 'Fill in the details to create a new ' . $modalTarget . '.'
                                    : 'Update the details for this ' . $modalTarget . '.' }}
                            </p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- MODAL BODY --}}
                    <div class="p-6 space-y-5">

                        {{-- ─── ROLE FORM ─── --}}
                        @if ($modalTarget === 'role')

                        {{-- Role Name --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Role Name</label>
                            <input wire:model="roleName"
                                   type="text"
                                   placeholder="e.g. department-head"
                                   class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 transition-colors">
                            @error('roleName')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-400">Spaces are converted to hyphens automatically.</p>
                        </div>

                        {{-- Permissions Block --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Permissions
                                    <span class="normal-case font-normal text-gray-400 ml-1">({{ count($rolePermissions) }} selected)</span>
                                </label>
                            </div>

                            <div class="border border-gray-200 rounded-lg overflow-hidden">

                                {{-- Permission Search --}}
                                <div class="p-3 border-b border-gray-100 bg-gray-50">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <input wire:model.live.debounce.300ms="modalPermissionSearch"
                                               type="text"
                                               placeholder="Search permissions..."
                                               class="pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                                    </div>
                                </div>

                                {{-- Select All / Deselect All --}}
                                <div class="flex items-center justify-between px-3 py-2 bg-indigo-50 border-b border-indigo-100">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox"
                                               :checked="$allFilteredSelected"
                                               wire:click="{{ $allFilteredSelected ? 'deselectAllPermissions' : 'selectAllPermissions' }}"
                                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                        <span class="text-xs font-semibold text-indigo-700">
                                            {{ $allFilteredSelected ? 'Deselect All' : 'Select All' }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-indigo-500">
                                        {{ $allPermissions->count() }} permission{{ $allPermissions->count() !== 1 ? 's' : '' }} shown
                                    </span>
                                </div>

                                {{-- Checkbox List --}}
                                <div class="max-h-52 overflow-y-auto divide-y divide-gray-50">
                                    @foreach ($allPermissions as $perm)
                                    <label class="flex items-center gap-3 px-3 py-2 hover:bg-indigo-50 cursor-pointer transition-colors duration-100">
                                        <input type="checkbox"
                                               wire:model="rolePermissions"
                                               value="{{ $perm->id }}"
                                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs text-gray-700">{{ $perm->name }}</span>
                                    </label>
                                    @endforeach

                                    @if ($allPermissions->isEmpty())
                                    <div class="px-3 py-4 text-center text-xs text-gray-400">No permissions match your search.</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @endif
                        {{-- ─── END ROLE FORM ─── --}}

                        {{-- ─── PERMISSION FORM ─── --}}
                        @if ($modalTarget === 'permission')
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Permission Name</label>
                            <input wire:model="permissionName"
                                   type="text"
                                   placeholder="e.g. department.create"
                                   class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 transition-colors">
                            @error('permissionName')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-400">Use dot notation for module grouping (e.g. <code class="bg-gray-100 px-1 rounded">module.action</code>).</p>
                        </div>
                        @endif
                        {{-- ─── END PERMISSION FORM ─── --}}
                    </div>

                    {{-- MODAL FOOTER --}}
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                        <button wire:click="closeModal"
                            class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                            Cancel
                        </button>
                        <button wire:click="{{ $modalTarget === 'role' ? 'saveRole' : 'savePermission' }}"
                            class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors duration-150 shadow-sm">
                            {{ $modalMode === 'create' ? 'Create' : 'Save Changes' }}
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- ============================================================ --}}
            {{-- DELETE CONFIRMATION MODAL                                      --}}
            {{-- ============================================================ --}}
            @if ($showDeleteConfirm)
            <div class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="fixed inset-0 bg-black bg-opacity-40" wire:click="cancelDelete"></div>

                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">
                    <div class="flex justify-center pt-6">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    </div>

                    <div class="px-6 pt-4 pb-2 text-center">
                        <h3 class="text-base font-bold text-gray-800">Delete {{ ucfirst($deleteTarget) }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            This action cannot be undone. The {{ $deleteTarget }} will be permanently removed
                            @if ($deleteTarget === 'role')
                                and all users assigned to it will lose this role.
                            @else
                                from all roles that currently have it.
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-3 px-6 py-5">
                        <button wire:click="cancelDelete"
                            class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                            Cancel
                        </button>
                        <button wire:click="{{ $deleteTarget === 'role' ? 'deleteRole' : 'deletePermission' }}"
                            class="px-5 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-150 shadow-sm">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>