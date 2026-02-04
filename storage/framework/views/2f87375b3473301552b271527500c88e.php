<div
    x-data="{
        showRestoreModal: <?php if ((object) ('showRestoreModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showRestoreModal'->value()); ?>')<?php echo e('showRestoreModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showRestoreModal'); ?>')<?php endif; ?>,
        showDeleteModal: <?php if ((object) ('showDeleteModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showDeleteModal'->value()); ?>')<?php echo e('showDeleteModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showDeleteModal'); ?>')<?php endif; ?>
    }">
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- ================= FLASH MESSAGES ================= -->
            <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <?php if(session()->has('error')): ?>
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <?php echo e(session('error')); ?>

            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- ================= HEADER ================= -->
            <div class="mb-8">
                <h1 class="text-4xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-2">
                    Archived Applicants
                </h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-box-archive text-[#1E7F3E]"></i>
                    Manage Archived Applicants
                </p>
            </div>

            <!-- ================= TABLE CARD ================= -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">

                <!-- TABLE HEADER -->
                <div class="bg-[#1E7F3E] p-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-white">Archived Applicants List</h2>

                    <select wire:model.live="perPage"
                        class="bg-white/90 rounded-lg px-4 py-2 text-sm">
                        <option value="5">5 / page</option>
                        <option value="10">10 / page</option>
                        <option value="25">25 / page</option>
                        <option value="50">50 / page</option>
                    </select>
                </div>

                <!-- TABLE -->
                <div class="p-6 overflow-x-auto">
                    <div class="flex justify-between mb-4 gap-4 flex-wrap">
                        <input type="text"
                            wire:model.live.debounce.300ms="search"
                            class="w-full md:w-1/3 border-gray-300 rounded-lg"
                            placeholder="Search applicant...">

                        <span class="text-sm bg-orange-100 border border-orange-300 px-4 py-2 rounded-lg">
                            <?php echo e($archivedApplicants->total()); ?> Archived Applicant<?php echo e($archivedApplicants->total() !== 1 ? 's' : ''); ?>

                        </span>
                    </div>

                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">No.</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Position</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Archived Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Action</th>
                            </tr>
                        </thead>

                        <tbody class="bg-gray-50 divide-y divide-gray-300">
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $archivedApplicants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $applicant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-100">
                                <td class="px-6 py-4">
                                    <?php echo e($archivedApplicants->firstItem() + $index); ?>

                                </td>
                                <td class="px-6 py-4 font-medium">
                                    <?php echo e($applicant->applicant->user->name ?? 'No Name'); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <?php echo e($applicant->applicant->user->email ?? 'No Email'); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <?php echo e($applicant->position->name ?? '—'); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fa-solid fa-archive mr-1"></i>
                                        Archived
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo e($applicant->updated_at->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button wire:click="openRestoreModal(<?php echo e($applicant->id); ?>)"
                                        class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fa-solid fa-rotate-left mr-1"></i>
                                        Restore
                                    </button>
                                    <button wire:click="openDeleteModal(<?php echo e($applicant->id); ?>)"
                                        class="bg-red-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-red-700 transition-colors duration-200">
                                        <i class="fa-solid fa-trash mr-1"></i>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-lg font-medium">No archived applicants found</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>

                    <!-- PAGINATION -->
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-xs text-gray-500">
                            Showing <?php echo e($archivedApplicants->firstItem() ?? 0); ?>

                            to <?php echo e($archivedApplicants->lastItem() ?? 0); ?>

                            of <?php echo e($archivedApplicants->total()); ?> applicants
                        </span>
                        <?php echo e($archivedApplicants->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- RESTORE CONFIRMATION MODAL FOR APPLICANTS                   -->
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
                @click="$wire.closeRestoreModal()"></div>

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
                @click.away="$wire.closeRestoreModal()">
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Restore Applicant</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to restore this applicant? They will appear in the active applicants list and can be processed again.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closeRestoreModal" type="button"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors duration-200">
                        Cancel
                    </button>
                    <button wire:click="restore" type="button"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors duration-200">
                        Restore
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- DELETE CONFIRMATION MODAL FOR APPLICANTS                    -->
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
                @click="$wire.closeDeleteModal()"></div>

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
                @click.away="$wire.closeDeleteModal()">
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Applicant Permanently</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to <span class="font-semibold text-red-600">permanently delete</span> this applicant? This action cannot be undone and all data will be lost.
                                </p>
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-xs text-red-800 font-medium">
                                        ⚠️ Warning: This is permanent. Consider restoring the applicant if you might need their data later.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closeDeleteModal" type="button"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors duration-200">
                        Cancel
                    </button>
                    <button wire:click="delete" type="button"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors duration-200">
                        Delete Permanently
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views/livewire/admin/archive-applicant-management.blade.php ENDPATH**/ ?>