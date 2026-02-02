<div>
    
    
    
    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
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
            <span class="text-sm font-medium"><?php echo e(session()->get('success')); ?></span>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(session()->has('error')): ?>
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
            <span class="text-sm font-medium"><?php echo e(session()->get('error')); ?></span>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">

            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Roles & Permissions</h1>
                <p class="text-gray-500 text-sm mt-1">Manage application roles and their associated permissions.</p>
            </div>

            
            <div class="flex gap-1 bg-white rounded-xl border border-gray-200 p-1 w-fit mb-6 shadow-sm">
                <button wire:click="switchTab('roles')"
                    class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                           <?php echo e($activeTab === 'roles' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    Roles
                    <span class="ml-2 text-xs opacity-70">(<?php echo e($roles->total()); ?>)</span>
                </button>
                <button wire:click="switchTab('permissions')"
                    class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                           <?php echo e($activeTab === 'permissions' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    Permissions
                    <span class="ml-2 text-xs opacity-70">(<?php echo e($permissions->total()); ?>)</span>
                </button>
            </div>

            
            
            
            <!--[if BLOCK]><![endif]--><?php if($activeTab === 'roles'): ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

                
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-5 border-b border-gray-100">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        
                        <div class="relative flex-1 sm:flex-none sm:w-64">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input wire:model.live.debounce.400ms="roleSearch"
                                   type="text"
                                   placeholder="Search roles..."
                                   class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                        </div>

                        
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs text-gray-500">Show</span>
                            <select wire:model.live="rolePerPage"
                                    class="text-sm border border-gray-200 rounded-lg px-2.5 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent cursor-pointer">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($option); ?>"><?php echo e($option); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <span class="text-xs text-gray-500">per page</span>
                        </div>
                    </div>

                    
                    <button wire:click="openCreateRole"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Role
                    </button>
                </div>

                
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
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-5 py-4 text-sm text-gray-500"><?php echo e($roles->firstItem() + $index); ?></td>
                                <td class="px-5 py-4">
                                    <span class="text-sm font-semibold text-gray-800"><?php echo e($role->name); ?></span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-500"><?php echo e($role->permissions_count); ?> permission<?php echo e($role->permissions_count !== 1 ? 's' : ''); ?></span>
                                </td>
                                <td class="px-5 py-4">
                                    <!--[if BLOCK]><![endif]--><?php if($this->isSystemRole($role->name)): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm6 4a1 1 0 11-2 0 1 1 0 012 0zm-6-4h12v2H5V9z" clip-rule="evenodd" />
                                            </svg>
                                            System
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                            Custom
                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!--[if BLOCK]><![endif]--><?php if($this->isSystemRole($role->name)): ?>
                                            <span class="text-xs text-gray-400 italic">Protected</span>
                                        <?php else: ?>
                                            <button wire:click="openEditRole(<?php echo e($role->id); ?>)"
                                                class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="confirmDeleteRole(<?php echo e($role->id); ?>)"
                                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if($roles->isEmpty()): ?>
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">No roles found.</td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if($roles->hasPages() || $roles->total() > 0): ?>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-100">
                    <span class="text-xs text-gray-500">
                        Showing <?php echo e($roles->firstItem()); ?> to <?php echo e($roles->lastItem()); ?> of <?php echo e($roles->total()); ?> role<?php echo e($roles->total() !== 1 ? 's' : ''); ?>

                    </span>
                    <div>
                        <?php echo e($roles->links()); ?>

                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            
            
            <!--[if BLOCK]><![endif]--><?php if($activeTab === 'permissions'): ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">

                
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-5 border-b border-gray-100">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        
                        <div class="relative flex-1 sm:flex-none sm:w-64">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input wire:model.live.debounce.400ms="permissionSearch"
                                   type="text"
                                   placeholder="Search permissions..."
                                   class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50">
                        </div>

                        
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-xs text-gray-500">Show</span>
                            <select wire:model.live="permissionPerPage"
                                    class="text-sm border border-gray-200 rounded-lg px-2.5 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent cursor-pointer">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($option); ?>"><?php echo e($option); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <span class="text-xs text-gray-500">per page</span>
                        </div>
                    </div>

                    
                    <button wire:click="openCreatePermission"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Permission
                    </button>
                </div>

                
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
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-5 py-4 text-sm text-gray-500"><?php echo e($permissions->firstItem() + $index); ?></td>
                                <td class="px-5 py-4">
                                    <span class="text-sm font-semibold text-gray-800"><?php echo e($permission->name); ?></span>
                                </td>
                                <td class="px-5 py-4">
                                    <?php
                                        $parts = explode('.', $permission->name);
                                        $module = $parts[0] ?? $permission->name;
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        <?php echo e($module); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-500"><?php echo e($permission->roles_count); ?> role<?php echo e($permission->roles_count !== 1 ? 's' : ''); ?></span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="openEditPermission(<?php echo e($permission->id); ?>)"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDeletePermission(<?php echo e($permission->id); ?>)"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                            title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if($permissions->isEmpty()): ?>
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">No permissions found.</td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if($permissions->hasPages() || $permissions->total() > 0): ?>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-100">
                    <span class="text-xs text-gray-500">
                        Showing <?php echo e($permissions->firstItem()); ?> to <?php echo e($permissions->lastItem()); ?> of <?php echo e($permissions->total()); ?> permission<?php echo e($permissions->total() !== 1 ? 's' : ''); ?>

                    </span>
                    <div>
                        <?php echo e($permissions->links()); ?>

                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            
            
            <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
            <div class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="fixed inset-0 bg-black bg-opacity-40" wire:click="closeModal"></div>

                <div class="relative bg-white rounded-2xl shadow-2xl w-full mx-4 overflow-hidden
                            <?php echo e($modalTarget === 'role' ? 'max-w-xl' : 'max-w-lg'); ?>">

                    
                    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50">
                        <div>
                            <h3 class="text-base font-bold text-gray-800">
                                <?php echo e($modalMode === 'create' ? 'Create' : 'Edit'); ?> <?php echo e(ucfirst($modalTarget)); ?>

                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <?php echo e($modalMode === 'create'
                                    ? 'Fill in the details to create a new ' . $modalTarget . '.'
                                    : 'Update the details for this ' . $modalTarget . '.'); ?>

                            </p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    
                    <div class="p-6 space-y-5">

                        
                        <!--[if BLOCK]><![endif]--><?php if($modalTarget === 'role'): ?>

                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Role Name</label>
                            <input wire:model="roleName"
                                   type="text"
                                   placeholder="e.g. department-head"
                                   class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 transition-colors">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['roleName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            <p class="mt-1.5 text-xs text-gray-400">Spaces are converted to hyphens automatically.</p>
                        </div>

                        
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Permissions
                                    <span class="normal-case font-normal text-gray-400 ml-1">(<?php echo e(count($rolePermissions)); ?> selected)</span>
                                </label>
                            </div>

                            <div class="border border-gray-200 rounded-lg overflow-hidden">

                                
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

                                
                                <div class="flex items-center justify-between px-3 py-2 bg-indigo-50 border-b border-indigo-100">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox"
                                               :checked="$allFilteredSelected"
                                               wire:click="<?php echo e($allFilteredSelected ? 'deselectAllPermissions' : 'selectAllPermissions'); ?>"
                                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                        <span class="text-xs font-semibold text-indigo-700">
                                            <?php echo e($allFilteredSelected ? 'Deselect All' : 'Select All'); ?>

                                        </span>
                                    </div>
                                    <span class="text-xs text-indigo-500">
                                        <?php echo e($allPermissions->count()); ?> permission<?php echo e($allPermissions->count() !== 1 ? 's' : ''); ?> shown
                                    </span>
                                </div>

                                
                                <div class="max-h-52 overflow-y-auto divide-y divide-gray-50">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $allPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-3 px-3 py-2 hover:bg-indigo-50 cursor-pointer transition-colors duration-100">
                                        <input type="checkbox"
                                               wire:model="rolePermissions"
                                               value="<?php echo e($perm->id); ?>"
                                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs text-gray-700"><?php echo e($perm->name); ?></span>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                                    <!--[if BLOCK]><![endif]--><?php if($allPermissions->isEmpty()): ?>
                                    <div class="px-3 py-4 text-center text-xs text-gray-400">No permissions match your search.</div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>

                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        

                        
                        <!--[if BLOCK]><![endif]--><?php if($modalTarget === 'permission'): ?>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Permission Name</label>
                            <input wire:model="permissionName"
                                   type="text"
                                   placeholder="e.g. department.create"
                                   class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 transition-colors">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['permissionName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1.5 text-xs text-red-500"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            <p class="mt-1.5 text-xs text-gray-400">Use dot notation for module grouping (e.g. <code class="bg-gray-100 px-1 rounded">module.action</code>).</p>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                    </div>

                    
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50">
                        <button wire:click="closeModal"
                            class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                            Cancel
                        </button>
                        <button wire:click="<?php echo e($modalTarget === 'role' ? 'saveRole' : 'savePermission'); ?>"
                            class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors duration-150 shadow-sm">
                            <?php echo e($modalMode === 'create' ? 'Create' : 'Save Changes'); ?>

                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            
            
            <!--[if BLOCK]><![endif]--><?php if($showDeleteConfirm): ?>
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
                        <h3 class="text-base font-bold text-gray-800">Delete <?php echo e(ucfirst($deleteTarget)); ?></h3>
                        <p class="text-sm text-gray-500 mt-1">
                            This action cannot be undone. The <?php echo e($deleteTarget); ?> will be permanently removed
                            <!--[if BLOCK]><![endif]--><?php if($deleteTarget === 'role'): ?>
                                and all users assigned to it will lose this role.
                            <?php else: ?>
                                from all roles that currently have it.
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-3 px-6 py-5">
                        <button wire:click="cancelDelete"
                            class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                            Cancel
                        </button>
                        <button wire:click="<?php echo e($deleteTarget === 'role' ? 'deleteRole' : 'deletePermission'); ?>"
                            class="px-5 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-150 shadow-sm">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views/livewire/admin/role-permission-manager.blade.php ENDPATH**/ ?>