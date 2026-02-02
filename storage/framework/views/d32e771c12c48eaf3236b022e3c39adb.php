<div x-data="{
    showDeleteConfirm: false,
    deleteId: null
}" 
@confirm-delete.window="
    const data = $event.detail;

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $wire.delete();
        }
    });
"
@alert.window="
    const data = $event.detail;

    if (data.type === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: data.message,
            timer: 3000,
            showConfirmButton: false
        });
    } 
    else if (data.type === 'error') {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: data.message,
            confirmButtonColor: '#3085d6'
        });
    }
"
class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section with Enhanced Styling -->
        <div class="mb-8 animate-fadeIn">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                        NBC Committee Management
                    </h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Manage NBC Committee members and their positions
                    </p>
                </div>
            </div>
        </div>

        <!-- Enhanced Table Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.2s;">
            <!-- Table Header with Filter -->
            <div class="bg-[#0a6025] p-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Left: Title -->
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <i class="fa-solid fa-users text-white text-lg"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Committee Members</h2>
                    </div>

                    <!-- Right: Controls -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Search -->
                        <div class="flex-1 min-w-[200px] max-w-md">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    wire:model.live.debounce.300ms="search"
                                    id="search"
                                    class="w-full pl-10 pr-4 py-2 bg-white/90 rounded-lg text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white/50 focus:outline-none"
                                    placeholder="Search by name, email, or position...">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                    <svg class="shrink-0 size-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Per Page -->
                        <select 
                            wire:model.live="perPage"
                            id="perPage"
                            class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700">
                            <option value="5">5 / page</option>
                            <option value="10">10 / page</option>
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                            <option value="100">100 / page</option>
                        </select>

                        <!-- Add Button -->
                        <button 
                            wire:click="openCreateModal"
                            class="bg-white/90 hover:bg-white text-[#0a6025] font-semibold px-6 py-2 rounded-lg transition duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Member
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">

                                <!-- Table -->
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase text-black">ID</span>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase text-black">Name</span>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase text-black">Email</span>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase text-black">Position</span>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <div class="flex items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase text-black">Created At</span>
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center">
                                                <div class="flex items-center gap-x-2">
                                                    <span class="text-xs font-semibold uppercase text-black">Actions</span>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-300 bg-gray-50">
                                        <?php $__empty_1 = true; $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $committee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="bg-gray-50 hover:bg-gray-100">
                                            <td class="size-px whitespace-nowrap align-top">
                                                <div class="text-sm font-semibold text-black">
                                                    <?php echo e($committees->firstItem() ? $committees->firstItem() + $loop->index : $loop->iteration); ?>

                                                </div>
                                            </td>
                                            <td class="size-px whitespace-nowrap align-top">
                                                <div class="text-sm font-semibold text-black"><?php echo e($committee->user->name); ?></div>
                                            </td>
                                            <td class="size-px whitespace-nowrap align-top">
                                                <div class="text-sm font-medium text-black"><?php echo e($committee->user->email); ?></div>
                                            </td>
                                            <td class="size-px whitespace-nowrap align-top">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php echo e($committee->position === 'evaluator' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800'); ?>">
                                                    <?php echo e(ucfirst($committee->position)); ?>

                                                </span>
                                            </td>
                                            <td class="size-px whitespace-nowrap align-top">
                                                <div class="text-sm font-medium text-black">
                                                    <?php echo e($committee->created_at->format('M j, Y')); ?>

                                                </div>
                                            </td>
                                            <td class="size-px whitespace-nowrap align-top">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button 
                                                        wire:click="openEditModal(<?php echo e($committee->id); ?>)"
                                                        class="inline-flex items-center px-3 py-1.5 bg-[#0a6025] text-white text-xs font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </button>
                                                    <button 
                                                        wire:click="confirmDelete(<?php echo e($committee->id); ?>)"
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition shadow-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p class="text-black text-lg font-medium">No NBC Committee members found</p>
                                                <p class="text-gray-600 text-sm mt-1">Start by adding a new member</p>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <!-- End Table -->
                                <div class="p-4">
                                    <?php echo e($committees->links()); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <?php if($showModal): ?>
    <div 
        x-data="{ show: <?php if ((object) ('showModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'->value()); ?>')<?php echo e('showModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'); ?>')<?php endif; ?> }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">
        
        <!-- Background overlay -->
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div 
                @click="$wire.closeModal()"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"></div>

            <!-- Center modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div 
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <!-- Modal Header -->
                <div class="bg-[#0a6025] px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white" id="modal-title">
                            <?php echo e($editMode ? 'Edit NBC Committee Member' : 'Add New NBC Committee Member'); ?>

                        </h3>
                        <button 
                            wire:click="closeModal"
                            class="text-white hover:text-gray-200 transition duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="save">
                    <div class="px-6 py-5 space-y-5">
                        
                        <!-- Name Input -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="name"
                                id="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Enter full name">
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

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                wire:model="email"
                                id="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Enter email address">
                            <?php $__errorArgs = ['email'];
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

                        <!-- Password Input -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <?php if(!$editMode): ?><span class="text-red-500">*</span><?php endif; ?>
                                <?php if($editMode): ?><span class="text-xs text-gray-500">(Leave blank to keep current password)</span><?php endif; ?>
                            </label>
                            <input 
                                type="password" 
                                wire:model="password"
                                id="password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Enter password">
                            <?php $__errorArgs = ['password'];
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

                        <!-- Password Confirmation Input -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password <?php if(!$editMode): ?><span class="text-red-500">*</span><?php endif; ?>
                            </label>
                            <input 
                                type="password" 
                                wire:model="password_confirmation"
                                id="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent"
                                placeholder="Confirm password">
                        </div>

                        <!-- Position Selection -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                Position <span class="text-red-500">*</span>
                            </label>
                            <select 
                                wire:model="position"
                                id="position"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">-- Select Position --</option>
                                <option value="evaluator">Evaluator</option>
                                <option value="verifier">Verifier</option>
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

                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition duration-200">
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="w-full sm:w-auto px-6 py-2 bg-[#0a6025] hover:bg-green-700 text-white font-semibold rounded-lg transition duration-200">
                            <?php echo e($editMode ? 'Update' : 'Create'); ?>

                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <?php endif; ?>

</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\admin\nbc-committee-manager.blade.php ENDPATH**/ ?>