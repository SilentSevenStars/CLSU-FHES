<div x-data="{ 
        showSuccessModal: false,
        showErrorModal: false,
        showDeleteConfirmModal: false,
        alertMessage: '',
        deleteId: null
     }" 
     @swal:confirm.window="
        deleteId = $event.detail.id;
        showDeleteConfirmModal = true;
     "
     <?php echo $__env->yieldSection(); ?>-alert.window="
        console.log('Event received:', $event.detail);
        alertMessage = $event.detail.message;
        if ($event.detail.type === 'success') {
            showSuccessModal = true;
            setTimeout(() => { showSuccessModal = false; }, 3000);
        } else {
            showErrorModal = true;
            setTimeout(() => { showErrorModal = false; }, 3000);
        }
     "
     x-init="
        window.addEventListener('show-alert', (event) => {
            console.log('Window event:', event.detail);
        });
     ">

    <div class="flex-1 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Representatives
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0B712C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Manage Representatives
                        </p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.3s;">
                <!-- Table Header -->
                <div class="bg-[#0a6025] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-users text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Representatives List</h2>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Per Page -->
                            <select wire:model.live="perPage"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white">
                                <option value="5">5 / page</option>
                                <option value="10">10 / page</option>
                                <option value="15">15 / page</option>
                                <option value="20">20 / page</option>
                                <option value="50">50 / page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden">
                                    <!-- Header -->
                                    <div class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-200 gap-3 bg-white">
                                        <!-- Search Input -->
                                        <div class="flex-1 min-w-[200px] max-w-md">
                                            <label for="search-representative" class="sr-only">Search</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live="search" id="search-representative"
                                                    class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-[#0B712C] focus:ring-[#0B712C] bg-white text-gray-900 placeholder-gray-500"
                                                    placeholder="Search by name or position...">
                                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                    <svg class="shrink-0 size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Create Button -->
                                        <div>
                                            <button wire:click="openCreateModal" 
                                                class="block text-white bg-[#0D7A2F] hover:bg-[#0a6025] focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                                <i class="fa-solid fa-plus mr-2"></i>Create Representative
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            #
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            Name
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            Position
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            Created At
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-center">
                                                    <div class="flex items-center justify-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-gray-800">
                                                            Actions
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-200">
                                            <?php $__empty_1 = true; $__currentLoopData = $representatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $representative): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="bg-white hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?php echo e($representatives->firstItem() + $index); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <?php echo e($representative->name); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?php echo e($representative->position); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?php echo e($representative->created_at->format('M d, Y')); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <button wire:click="openEditModal(<?php echo e($representative->id); ?>)"
                                                            class="inline-flex items-center gap-x-1 text-sm font-medium text-yellow-600 hover:text-yellow-700 bg-yellow-50 hover:bg-yellow-100 px-3 py-1.5 rounded-lg transition-colors">
                                                            <i class="fa-solid fa-edit"></i>
                                                            Edit
                                                        </button>
                                                        <button wire:click="confirmDelete(<?php echo e($representative->id); ?>)"
                                                            class="inline-flex items-center gap-x-1 text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                                            <i class="fa-solid fa-trash"></i>
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="5" class="px-6 py-8 text-center">
                                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                                        <i class="fa-solid fa-inbox text-4xl mb-3"></i>
                                                        <p class="text-sm font-medium">No representatives found</p>
                                                        <p class="text-xs mt-1">Try adjusting your search criteria</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="px-6 py-4 border-t border-gray-200">
                                        <?php echo e($representatives->links()); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteConfirmModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showDeleteConfirmModal"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden"
                 @click.stop>
                
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <i class="fa-solid fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Are you sure?</h3>
                    <p class="text-sm text-gray-600 text-center mb-6">This action cannot be undone.</p>
                    
                    <div class="flex gap-3">
                        <button @click="showDeleteConfirmModal = false"
                                class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button @click="$wire.deleteConfirmed(deleteId); showDeleteConfirmModal = false;"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                            Yes, delete it!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccessModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed top-4 right-4 z-50 w-96" 
         style="display: none;">
        
        <div class="bg-white rounded-lg shadow-xl border-l-4 border-green-500 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
                        <i class="fa-solid fa-check text-green-600 text-lg"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-bold text-gray-900">Success!</h3>
                    <p class="text-sm text-gray-600 mt-1" x-text="alertMessage"></p>
                </div>
                <button @click="showSuccessModal = false" class="ml-3 text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div x-show="showErrorModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed top-4 right-4 z-50 w-96" 
         style="display: none;">
        
        <div class="bg-white rounded-lg shadow-xl border-l-4 border-red-500 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                        <i class="fa-solid fa-times text-red-600 text-lg"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-bold text-gray-900">Error!</h3>
                    <p class="text-sm text-gray-600 mt-1" x-text="alertMessage"></p>
                </div>
                <button @click="showErrorModal = false" class="ml-3 text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div x-data="{ show: <?php if ((object) ('showCreateModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showCreateModal'->value()); ?>')<?php echo e('showCreateModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showCreateModal'); ?>')<?php endif; ?> }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
             @click="$wire.closeCreateModal()"></div>

        <!-- Modal -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden"
                 @click.stop>
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-[#0D7A2F] to-[#0a6025] px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-user-plus text-white text-lg"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Create New Representative</h3>
                        </div>
                        <button @click="$wire.closeCreateModal()" 
                                class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                            <i class="fa-solid fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="store" class="p-6 space-y-4">
                    <!-- Name Field -->
                    <div>
                        <label for="create-name" class="block text-sm font-medium text-gray-700 mb-2">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               wire:model="name" 
                               id="create-name"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0D7A2F] focus:border-transparent transition-all"
                               placeholder="Enter representative name">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Position Field -->
                    <div>
                        <label for="create-position" class="block text-sm font-medium text-gray-700 mb-2">
                            Position <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="position" 
                                id="create-position"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0D7A2F] focus:border-transparent transition-all">
                            <option value="">Select Position</option>
                            <?php $__currentLoopData = $availablePositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pos); ?>"><?php echo e($pos); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" 
                                @click="$wire.closeCreateModal()"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            <i class="fa-solid fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-[#0D7A2F] hover:bg-[#0a6025] rounded-lg transition-colors">
                            <i class="fa-solid fa-save mr-2"></i>Create Representative
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-data="{ show: <?php if ((object) ('showEditModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showEditModal'->value()); ?>')<?php echo e('showEditModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showEditModal'); ?>')<?php endif; ?> }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
             @click="$wire.closeEditModal()"></div>

        <!-- Modal -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden"
                 @click.stop>
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-user-edit text-white text-lg"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Edit Representative</h3>
                        </div>
                        <button @click="$wire.closeEditModal()" 
                                class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                            <i class="fa-solid fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="update" class="p-6 space-y-4">
                    <!-- Name Field -->
                    <div>
                        <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-2">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               wire:model="name" 
                               id="edit-name"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all"
                               placeholder="Enter representative name">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Position Field -->
                    <div>
                        <label for="edit-position" class="block text-sm font-medium text-gray-700 mb-2">
                            Position <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="position" 
                                id="edit-position"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                            <option value="">Select Position</option>
                            <?php $__currentLoopData = $availablePositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pos); ?>"><?php echo e($pos); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" 
                                @click="$wire.closeEditModal()"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            <i class="fa-solid fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg transition-colors">
                            <i class="fa-solid fa-save mr-2"></i>Update Representative
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\admin\representative-manager.blade.php ENDPATH**/ ?>