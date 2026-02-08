<div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 animate-fadeIn">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-2">
                        Screening
                    </h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1E7F3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        View and export screening results for faculty positions
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
                            <i class="fa-solid fa-clipboard-check text-white text-lg"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Screening Results</h2>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-300 gap-3">
                <!-- Search -->
                <div class="flex-1 min-w-[200px] max-w-md">
                    <label class="sr-only">Search</label>
                    <div class="relative">
                        <input 
                            type="text"
                            wire:model.live.debounce.300ms="searchTerm"
                            class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]"
                            placeholder="Search applicant..."
                        />
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                            <svg class="shrink-0 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Position Filter -->
                <div class="min-w-[200px]">
                    <select 
                        wire:model.live="selectedPosition"
                        class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]"
                    >
                        <option value="">Select Position</option>
                        <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($position); ?>">
                                <?php echo e($position); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="min-w-[200px]">
                    <select 
                        wire:model.live="selectedDate"
                        class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E] disabled:opacity-50 disabled:cursor-not-allowed"
                        <?php echo e(!$selectedPosition ? 'disabled' : ''); ?>

                    >
                        <option value="">Select Interview Date</option>
                        <?php $__currentLoopData = $interviewDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($date); ?>">
                                <?php echo e(date('M d, Y', strtotime($date))); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if(!$selectedPosition): ?>
                        <p class="text-xs text-gray-500 mt-1">Please select a position first</p>
                    <?php endif; ?>
                </div>

                <!-- Export -->
                <div>
                    <button 
                        wire:click="export"
                        class="block text-white bg-[#156B2D] hover:bg-[#125A26] focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                        <?php echo e(!$selectedPosition || !$selectedDate ? 'disabled' : ''); ?>

                    >
                        <i class="fa-solid fa-file-export mr-2"></i>Export PDF
                    </button>
                </div>
            </div>

            <!-- Table Section -->
            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <span class="text-xs font-semibold uppercase text-black">Name of Applicant</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start">
                                                <span class="text-xs font-semibold uppercase text-black">Field of Specialization</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center">
                                                <span class="text-xs font-semibold uppercase text-black">Performance</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center">
                                                <span class="text-xs font-semibold uppercase text-black">Credentials & Experience</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center">
                                                <span class="text-xs font-semibold uppercase text-black">Interview</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center">
                                                <span class="text-xs font-semibold uppercase text-black">Total</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center">
                                                <span class="text-xs font-semibold uppercase text-black">Rank</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-300 bg-gray-50">
                                        <?php $__empty_1 = true; $__currentLoopData = $screeningData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-semibold">
                                                    <?php echo e($data['name']); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    <?php echo e($data['specialization']); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black text-center">
                                                    <?php echo e($data['performance']); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black text-center">
                                                    <?php echo e($data['credentials_experience']); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black text-center">
                                                    <?php echo e($data['interview']); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-bold text-center">
                                                    <?php echo e($data['total']); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#1E7F3E] text-white font-bold">
                                                        <?php echo e($data['rank']); ?>

                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-12 text-gray-500">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                        <?php if(!$selectedPosition): ?>
                                                            <p class="text-lg font-medium">Please select a position</p>
                                                            <p class="text-sm mt-1">Choose a position from the filter above</p>
                                                        <?php elseif(!$selectedDate): ?>
                                                            <p class="text-lg font-medium">Please select an interview date</p>
                                                            <p class="text-sm mt-1">Choose a date from the filter above</p>
                                                        <?php else: ?>
                                                            <p class="text-lg font-medium">No completed evaluations found</p>
                                                            <p class="text-sm mt-1">All panel assignments must be completed</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(count($screeningData) > 0): ?>
                <div class="px-6 py-4 border-t border-gray-300 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-semibold"><?php echo e(count($screeningData)); ?></span> result<?php echo e(count($screeningData) !== 1 ? 's' : ''); ?>

                    </div>
                    <div class="text-xs text-gray-500">
                        * Only applicants with completed panel evaluations are shown
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\admin\screening.blade.php ENDPATH**/ ?>