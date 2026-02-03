<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section with Enhanced Styling -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1
                            class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Scheduled Applicants
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            List of scheduled applicant interviews
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
                                <i class="fa-solid fa-calendar-check text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Scheduled Applicant List</h2>
                        </div>

                        <!-- Right: Filter and Export -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Position Filter -->
                            <select wire:model.live="selectedPosition"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="">Filter by Position</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pos->id); ?>">
                                    <?php echo e($pos->name); ?>

                                    (<?php echo e(\Carbon\Carbon::parse($pos->start_date)->format('M j, Y')); ?>

                                    -
                                    <?php echo e(\Carbon\Carbon::parse($pos->end_date)->format('M j, Y')); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>

                            <!--[if BLOCK]><![endif]--><?php if($selectedPosition): ?>
                            <!-- Export Buttons -->
                            <button wire:click="exportExcel"
                                class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition shadow-sm">
                                <i class="fa-solid fa-file-excel mr-2"></i>
                                Export Excel
                            </button>

                            <button wire:click="exportPDF"
                                class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition shadow-sm">
                                <i class="fa-solid fa-file-pdf mr-2"></i>
                                Export PDF
                            </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <!-- Card -->
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div
                                    class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                    
                                    
                                    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
                                    <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                        <p class="text-sm text-green-800 leading-5 tracking-wide"><?php echo e(session('success')); ?></p>
                                    </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                    <?php if(session()->has('error')): ?>
                                    <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm text-red-800 leading-5 tracking-wide"><?php echo e(session('error')); ?></p>
                                    </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-black">
                                                            Applicant Name
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-black">
                                                            Email
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-black">
                                                            Applied Position
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-black">
                                                            Interview Scheduled
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr
                                                class="bg-gray-50 hover:bg-gray-100">
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-semibold text-black">
                                                        <?php echo e($application->applicant->user->name); ?>

                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        <?php echo e($application->applicant->user->email); ?>

                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        <?php echo e($application->position->name); ?>

                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        <!--[if BLOCK]><![endif]--><?php if($application->evaluation && $application->evaluation->interview_date): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($application->evaluation->interview_date)->format('M j, Y')); ?>

                                                        <?php else: ?>
                                                            <span class="text-gray-600">Not scheduled</span>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center">
                                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p class="text-black text-lg font-medium">No scheduled applicants found</p>
                                                    <p class="text-gray-600 text-sm mt-1">
                                                        <!--[if BLOCK]><![endif]--><?php if($selectedPosition): ?>
                                                            No applicants scheduled for this position yet
                                                        <?php else: ?>
                                                            Please select a position to view scheduled applicants
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </p>
                                                </td>
                                            </tr>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </tbody>
                                    </table>
                                    <!-- End Table -->
                                    <!--[if BLOCK]><![endif]--><?php if($applications->hasPages()): ?>
                                    <div class="p-4">
                                        <?php echo e($applications->links()); ?>

                                    </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Table Section -->
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views/livewire/admin/scheduled-applicant.blade.php ENDPATH**/ ?>