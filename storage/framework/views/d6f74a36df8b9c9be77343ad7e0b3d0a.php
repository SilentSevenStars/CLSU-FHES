<div>
    
    
    
    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(session()->has('error')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo e(session('error')); ?></span>
            <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 0 0 1 0 1.698z"/>
                </svg>
            </button>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-2">
                            Roles & Permissions
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#1E7F3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            Manage application roles and their associated permissions
                        </p>
                    </div>
                </div>
            </div>

            
            <div class="flex gap-1 bg-white rounded-xl border border-gray-200 p-1 w-fit mb-6 shadow-sm">
                <button wire:click="switchTab('roles')"
                    class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                           <?php echo e($activeTab === 'roles' ? 'bg-[#1E7F3E] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    Roles
                    <span class="ml-2 text-xs opacity-70">(<?php echo e($roles->total()); ?>)</span>
                </button>
                <button wire:click="switchTab('permissions')"
                    class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                           <?php echo e($activeTab === 'permissions' ? 'bg-[#1E7F3E] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    Permissions
                    <span class="ml-2 text-xs opacity-70">(<?php echo e($permissions->total()); ?>)</span>
                </button>
            </div>

            
            
            
            <!--[if BLOCK]><![endif]--><?php if($activeTab === 'roles'): ?>
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">

                
                <div class="bg-[#1E7F3E] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-user-shield text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Roles List</h2>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <select wire:model.live="rolePerPage"
                                    class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($option); ?>"><?php echo e($option); ?> / page</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-300 gap-3">
                    <div class="flex-1 min-w-[200px] max-w-md">
                        <label class="sr-only">Search</label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="roleSearch"
                                   class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]"
                                   placeholder="Search roles...">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                <svg class="shrink-0 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    
                    <button wire:click="openCreateRole"
                        class="block text-white bg-[#156B2D] hover:bg-[#125A26] focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        <i class="fa-solid fa-plus mr-2"></i>Create Role
                    </button>
                </div>

                
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">#</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Role Name</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Permissions</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Type</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Action</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-medium">
                                                    <?php echo e($roles->firstItem() + $index); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-semibold">
                                                    <?php echo e($role->name); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    <?php echo e($role->permissions_count); ?> permission<?php echo e($role->permissions_count !== 1 ? 's' : ''); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <!--[if BLOCK]><![endif]--><?php if($this->isSystemRole($role->name)): ?>
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                            System
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Custom
                                                        </span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <!--[if BLOCK]><![endif]--><?php if($this->isSystemRole($role->name)): ?>
                                                        <span class="text-xs text-gray-400 italic">Protected</span>
                                                    <?php else: ?>
                                                        <button wire:click="openEditRole(<?php echo e($role->id); ?>)"
                                                            class="text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg px-3 py-1 text-sm font-medium mr-2">
                                                            Edit
                                                        </button>
                                                        <button wire:click="confirmDeleteRole(<?php echo e($role->id); ?>)"
                                                            class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                                                            Delete
                                                        </button>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                                            <!--[if BLOCK]><![endif]--><?php if($roles->isEmpty()): ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-8 text-gray-500">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                            </path>
                                                        </svg>
                                                        <p class="text-lg font-medium">No roles found</p>
                                                        <button wire:click="openCreateRole"
                                                                class="mt-4 text-white bg-[#1E7F3E] hover:bg-[#156B2D] rounded-lg px-4 py-2 text-sm font-medium">
                                                            Create First Role
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if($roles->hasPages() || $roles->total() > 0): ?>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-300">
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
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">

                
                <div class="bg-[#1E7F3E] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-key text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Permissions List</h2>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <select wire:model.live="permissionPerPage"
                                    class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($option); ?>"><?php echo e($option); ?> / page</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-300 gap-3">
                    <div class="flex-1 min-w-[200px] max-w-md">
                        <label class="sr-only">Search</label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="permissionSearch"
                                   class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]"
                                   placeholder="Search permissions...">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                <svg class="shrink-0 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    
                    <button wire:click="openCreatePermission"
                        class="block text-white bg-[#156B2D] hover:bg-[#125A26] focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        <i class="fa-solid fa-plus mr-2"></i>Create Permission
                    </button>
                </div>

                
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">#</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Permission Name</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Module</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Assigned To</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Action</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-medium">
                                                    <?php echo e($permissions->firstItem() + $index); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-semibold">
                                                    <?php echo e($permission->name); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <?php
                                                        $parts = explode('.', $permission->name);
                                                        $module = $parts[0] ?? $permission->name;
                                                    ?>
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                        <?php echo e($module); ?>

                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    <?php echo e($permission->roles_count); ?> role<?php echo e($permission->roles_count !== 1 ? 's' : ''); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button wire:click="openEditPermission(<?php echo e($permission->id); ?>)"
                                                        class="text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg px-3 py-1 text-sm font-medium mr-2">
                                                        Edit
                                                    </button>
                                                    <button wire:click="confirmDeletePermission(<?php echo e($permission->id); ?>)"
                                                        class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                                            <!--[if BLOCK]><![endif]--><?php if($permissions->isEmpty()): ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-8 text-gray-500">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                            </path>
                                                        </svg>
                                                        <p class="text-lg font-medium">No permissions found</p>
                                                        <button wire:click="openCreatePermission"
                                                                class="mt-4 text-white bg-[#1E7F3E] hover:bg-[#156B2D] rounded-lg px-4 py-2 text-sm font-medium">
                                                            Create First Permission
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if($permissions->hasPages() || $permissions->total() > 0): ?>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-300">
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
                                   class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent bg-gray-50 transition-colors">
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
                                               class="pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent bg-white">
                                    </div>
                                </div>

                                
                                <div class="flex items-center justify-between px-3 py-2 bg-green-50 border-b border-green-100">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox"
                                               :checked="$allFilteredSelected"
                                               wire:click="<?php echo e($allFilteredSelected ? 'deselectAllPermissions' : 'selectAllPermissions'); ?>"
                                               class="w-4 h-4 rounded border-gray-300 text-[#1E7F3E] focus:ring-[#1E7F3E] cursor-pointer">
                                        <span class="text-xs font-semibold text-[#1E7F3E]">
                                            <?php echo e($allFilteredSelected ? 'Deselect All' : 'Select All'); ?>

                                        </span>
                                    </div>
                                    <span class="text-xs text-[#1E7F3E]">
                                        <?php echo e($allPermissions->count()); ?> permission<?php echo e($allPermissions->count() !== 1 ? 's' : ''); ?> shown
                                    </span>
                                </div>

                                
                                <div class="max-h-52 overflow-y-auto divide-y divide-gray-50">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $allPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-3 px-3 py-2 hover:bg-green-50 cursor-pointer transition-colors duration-100">
                                        <input type="checkbox"
                                               wire:model="rolePermissions"
                                               value="<?php echo e($perm->id); ?>"
                                               class="w-4 h-4 rounded border-gray-300 text-[#1E7F3E] focus:ring-[#1E7F3E]">
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
                                   class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent bg-gray-50 transition-colors">
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
                            class="px-5 py-2 text-sm font-semibold text-white bg-[#1E7F3E] rounded-lg hover:bg-[#156B2D] transition-colors duration-150 shadow-sm">
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
</div><?php /**PATH C:\Users\Owner\Desktop\projects\CLSU CAPS\resources\views/livewire/admin/role-permission-manager.blade.php ENDPATH**/ ?>