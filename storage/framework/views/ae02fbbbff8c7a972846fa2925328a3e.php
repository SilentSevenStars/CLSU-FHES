<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Screening</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            <!-- Search -->
            <div class="relative">
                <input 
                    type="text"
                    wire:model.live.debounce.300ms="searchTerm"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="Search applicant"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Position Filter -->
            <div>
                <select 
                    wire:model.live="selectedPosition"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
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
            <div>
                <select 
                    wire:model.live="selectedDate"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
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
                    class="w-full px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                    <?php echo e(!$selectedPosition || !$selectedDate ? 'disabled' : ''); ?>

                >
                    Export
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Name of Applicant
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Field of Specialization
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Performance
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Credentials & Experience
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Interview
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Total
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Rank
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $screeningData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium">
                                <?php echo e($data['name']); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                 <?php echo e($data['specialization']); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <?php echo e($data['performance']); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <?php echo e($data['credentials_experience']); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <?php echo e($data['interview']); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-center font-semibold">
                                <?php echo e($data['total']); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-700 font-bold">
                                    <?php echo e($data['rank']); ?>

                                </span>
                            </td>
                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
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
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(count($screeningData) > 0): ?>
            <div class="mt-4 text-sm text-gray-600 flex justify-between">
                <div>Showing <?php echo e(count($screeningData)); ?> result(s)</div>
                <div class="text-xs text-gray-500">
                    * Only applicants with completed panel evaluations are shown
                </div>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\admin\screening.blade.php ENDPATH**/ ?>