<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold text-[#0A6025] mb-2">
                            Panel Dashboard
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Evaluate scheduled applicants for today
                        </p>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <?php if(session()->has('message')): ?>
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 bg-green-50 border-l-4 border-green-500 p-6 rounded-lg shadow-lg animate-fadeIn">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-700 font-semibold text-lg"><?php echo e(session('message')); ?></p>
                        </div>
                        <button @click="show = false" class="text-green-500 hover:text-green-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!$panel): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg shadow-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-700 font-semibold text-lg">You are not registered as a panel member.</p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-[#0A6025] transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Scheduled</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                    <?php echo e($totalCount); ?>

                                </h3>
                            </div>
                            <div class="bg-[#0A6025] rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-green-500 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Completed</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                    <?php echo e($completedCount); ?>

                                </h3>
                            </div>
                            <div class="bg-green-500 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-yellow-500 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Pending</p>
                                <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                    <?php echo e($pendingCount); ?>

                                </h3>
                            </div>
                            <div class="bg-yellow-500 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search"
                               type="text"
                               placeholder="Search by applicant name or email..."
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] transition-all duration-200 shadow-sm">
                    </div>
                </div>

                <!-- Applications Table -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                    <!-- Table Header -->
                    <div class="bg-[#0A6025] p-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Scheduled Applicants for Today</h2>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800 tracking-wider">Applicant</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800 tracking-wider">Position</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800 tracking-wider">Interview Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800 tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800 tracking-wider">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $evaluation = $app->evaluation;
                                        $assignment = $assignments[$evaluation->id] ?? null;
                                        $isComplete = $assignment && $assignment->status === 'complete';
                                        $panelPos = strtolower($panel->panel_position);
                                    ?>

                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <!-- Applicant -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-[#0A6025] flex items-center justify-center text-white font-semibold">
                                                        <?php echo e(strtoupper(substr($app->applicant->first_name, 0, 1) . substr($app->applicant->last_name, 0, 1))); ?>

                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        <?php echo e($app->applicant->first_name); ?> <?php echo e($app->applicant->last_name); ?>

                                                    </div>
                                                    <div class="text-sm text-gray-500"><?php echo e($app->applicant->user->email); ?></div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Position -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900"><?php echo e($app->position->position); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo e($app->position->name); ?></div>
                                        </td>

                                        <!-- Interview Date -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-medium">
                                                <?php echo e(\Carbon\Carbon::parse($evaluation->interview_date)->format('F d, Y')); ?>

                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($isComplete): ?>
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Action -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if(! $isComplete): ?>
                                                <?php if($panelPos === 'head'): ?>
                                                    <a href="<?php echo e(route('panel.experience', $evaluation->id)); ?>"
                                                       class="inline-flex items-center px-4 py-2 bg-[#0A6025] hover:bg-[#0B712C] text-white font-semibold rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Evaluate
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('panel.interview', $evaluation->id)); ?>"
                                                       class="inline-flex items-center px-4 py-2 bg-[#0A6025] hover:bg-[#0B712C] text-white font-semibold rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Evaluate
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <button disabled
                                                    class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-600 font-semibold rounded-lg opacity-70 cursor-not-allowed">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Completed
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <p class="text-gray-500 text-lg font-medium">No scheduled applicants today.</p>
                                                <p class="text-gray-400 text-sm mt-1">Check back later for scheduled interviews.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($applications->hasPages()): ?>
                        <div class="px-6 py-4 border-t border-gray-200">
                            <?php echo e($applications->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\panel\dashboard.blade.php ENDPATH**/ ?>