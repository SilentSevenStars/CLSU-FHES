<div
    x-data="{
        showRestoreModal: <?php if ((object) ('showRestoreModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showRestoreModal'->value()); ?>')<?php echo e('showRestoreModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showRestoreModal'); ?>')<?php endif; ?>,
        showDeleteModal: <?php if ((object) ('showDeleteModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showDeleteModal'->value()); ?>')<?php echo e('showDeleteModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showDeleteModal'); ?>')<?php endif; ?>
    }"
>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Flash Messages -->
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

            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-2">
                            Archived Users
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#1E7F3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                </path>
                            </svg>
                            Manage Archived Users
                        </p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                <!-- Table Header -->
                <div class="bg-[#1E7F3E] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-box-archive text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Archived Users List</h2>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <select wire:model.live="perPage"
                                    class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="5">5 / page</option>
                                <option value="10">10 / page</option>
                                <option value="25">25 / page</option>
                                <option value="50">50 / page</option>
                                <option value="100">100 / page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                    <!-- Header -->
                                    <div class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-300 gap-3">
                                        <!-- Search Input -->
                                        <div class="flex-1 min-w-[200px] max-w-md">
                                            <label class="sr-only">Search</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live.debounce.300ms="search"
                                                       class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]"
                                                       placeholder="Search by name or email...">
                                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                    <svg class="shrink-0 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Info Badge -->
                                        <div class="flex items-center gap-2 bg-orange-50 border border-orange-200 rounded-lg px-4 py-2">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-medium text-orange-800">
                                                <?php echo e($archivedUsers->total()); ?> Archived User<?php echo e($archivedUsers->total() !== 1 ? 's' : ''); ?>

                                            </span>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">No.</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Name</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Email</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Role</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Archived Date</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Action</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $archivedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php
                                                    // Get the first role name via Spatie relationship
                                                    $roleName = $user->roles->first()?->name ?? 'none';

                                                    // Determine badge color based on role name
                                                    $badgeClass = match($roleName) {
                                                        'admin'       => 'bg-purple-100 text-purple-800',
                                                        'super-admin' => 'bg-indigo-100 text-indigo-800',
                                                        'panel'       => 'bg-blue-100 text-blue-800',
                                                        'nbc'         => 'bg-green-100 text-green-800',
                                                        default       => 'bg-amber-100 text-amber-800',  // any custom role
                                                    };
                                                ?>
                                                <tr class="bg-gray-50 hover:bg-gray-100">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-medium">
                                                        <?php echo e($archivedUsers->firstItem() + $index); ?>

                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                        <div class="flex items-center gap-2">
                                                            <span class="inline-flex items-center justify-center w-6 h-6 bg-orange-100 text-orange-600 rounded-full text-xs font-medium">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                            <?php echo e($user->name); ?>

                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                        <?php echo e($user->email); ?>

                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($badgeClass); ?>">
                                                            <?php echo e(ucfirst(str_replace('-', ' ', $roleName))); ?>

                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                        <?php echo e($user->updated_at->format('M d, Y')); ?>

                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <button wire:click="openRestoreModal(<?php echo e($user->id); ?>)"
                                                                class="text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-3 py-1 text-sm font-medium">
                                                            Restore
                                                        </button>
                                                        <button wire:click="openDeleteModal(<?php echo e($user->id); ?>)"
                                                                class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center py-8 text-gray-500">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                                </path>
                                                            </svg>
                                                            <p class="text-lg font-medium">No archived users found</p>
                                                            <p class="text-sm text-gray-400 mt-2">All users are active</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="p-4 bg-white border-t border-gray-300 flex flex-col sm:flex-row items-center justify-between gap-3">
                                        <span class="text-xs text-gray-500">
                                            <!--[if BLOCK]><![endif]--><?php if($archivedUsers->total() > 0): ?>
                                                Showing <?php echo e($archivedUsers->firstItem()); ?> to <?php echo e($archivedUsers->lastItem()); ?> of <?php echo e($archivedUsers->total()); ?> archived user<?php echo e($archivedUsers->total() !== 1 ? 's' : ''); ?>

                                            <?php else: ?>
                                                No archived users found
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </span>
                                        <?php echo e($archivedUsers->links()); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- RESTORE CONFIRMATION MODAL                                     -->
    <!-- ============================================================ -->
    <div x-show="showRestoreModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div
                x-show="showRestoreModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                @click="$wire.closeRestoreModal()"
            ></div>

            <!-- Modal panel -->
            <div
                x-show="showRestoreModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                @click.away="$wire.closeRestoreModal()"
            >
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Restore User</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to restore this user? The user will be able to log in again and will appear in the active users list.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closeRestoreModal" type="button"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                        Cancel
                    </button>
                    <button wire:click="restore" type="button"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Restore
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- DELETE CONFIRMATION MODAL                                      -->
    <!-- ============================================================ -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div
                x-show="showDeleteModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                @click="$wire.closeDeleteModal()"
            ></div>

            <!-- Modal panel -->
            <div
                x-show="showDeleteModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                @click.away="$wire.closeDeleteModal()"
            >
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete User Permanently</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to <span class="font-semibold text-red-600">permanently delete</span> this user? This action cannot be undone and all user data will be lost forever.
                                </p>
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-xs text-red-800 font-medium">
                                        ⚠️ Warning: This is a permanent action. Consider restoring the user instead if you might need their data later.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closeDeleteModal" type="button"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                        Cancel
                    </button>
                    <button wire:click="delete" type="button"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                        Delete Permanently
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div><?php /**PATH C:\Users\Owner\Desktop\projects\CLSU CAPS\resources\views/livewire/admin/archive-user-management.blade.php ENDPATH**/ ?>