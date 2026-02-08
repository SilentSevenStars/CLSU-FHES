<div class="min-h-screen bg-gray-50 py-8"
    x-data="{
        showChoiceModal: false,
        selectedEvaluationId: null
    }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
            $nbcCommittee = \App\Models\NbcCommittee::where('user_id', auth()->id())->first();
        ?>

        <?php if(!$nbcCommittee): ?>
            <!-- Unauthorized Access Message -->
            <div class="flex items-center justify-center min-h-[70vh]">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100">
                        <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h2 class="mt-6 text-3xl font-bold text-gray-900">Access Denied</h2>
                    <p class="mt-2 text-lg text-gray-600">You are not registered as an NBC Committee member.</p>
                    <p class="mt-2 text-sm text-gray-500">Please contact the administrator if you believe this is an error.</p>
                    <div class="mt-8">
                        <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Authorized Content -->
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">NBC Dashboard</h1>
            </div>

            <!-- Infographics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Pending Today Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Pending Today</p>
                            <p class="mt-2 text-4xl font-bold text-gray-900"><?php echo e($pendingTodayCount); ?></p>
                            <p class="mt-1 text-sm text-gray-500">Evaluations awaiting completion</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Complete Today Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Complete Today</p>
                            <p class="mt-2 text-4xl font-bold text-gray-900"><?php echo e($completeTodayCount); ?></p>
                            <p class="mt-1 text-sm text-gray-500">Evaluations completed today</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input 
                            type="text" 
                            id="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Name or Position..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Per Page -->
                    <div>
                        <label for="perPage" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                        <select 
                            id="perPage"
                            wire:model.live="perPage"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Evaluations Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Position
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $evaluations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evaluation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo e($evaluation->jobApplication->applicant->full_name); ?>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            <?php echo e($evaluation->jobApplication->applicant->user->email); ?>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?php echo e($evaluation->jobApplication->position->name); ?>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            // Check if current user has an assignment for this evaluation
                                            $userAssignment = \App\Models\NbcAssignment::where('evaluation_id', $evaluation->id)
                                                ->whereHas('nbcCommittee', function($q) {
                                                    $q->where('user_id', auth()->id());
                                                })
                                                ->first();
                                        ?>
                                        
                                        <?php if($userAssignment && $userAssignment->status === 'complete'): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Complete
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Pending
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php
                                            // Re-check assignment for the button state
                                            $userAssignment = \App\Models\NbcAssignment::where('evaluation_id', $evaluation->id)
                                                ->whereHas('nbcCommittee', function($q) {
                                                    $q->where('user_id', auth()->id());
                                                })
                                                ->first();
                                            $isComplete = $userAssignment && $userAssignment->status === 'complete';
                                        ?>
                                        
                                        <?php if($isComplete): ?>
                                            <!-- Disabled Button for Completed Evaluations -->
                                            <button 
                                                disabled
                                                class="inline-flex items-center px-3 py-1 bg-gray-400 text-white rounded-md cursor-not-allowed opacity-60"
                                                title="Evaluation already completed"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Completed
                                            </button>
                                        <?php else: ?>
                                            <!-- Active Button for Pending Evaluations -->
                                            <button 
                                                @click="selectedEvaluationId = <?php echo e($evaluation->id); ?>; showChoiceModal = true"
                                                class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Evaluate
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="mt-4 text-sm text-gray-500">No evaluations found</p>
                                        <?php if($search): ?>
                                            <button 
                                                wire:click="$set('search', '')"
                                                class="mt-2 text-sm text-blue-600 hover:text-blue-800"
                                            >
                                                Clear filters
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <?php echo e($evaluations->links()); ?>

                </div>
            </div>

            <!-- Results Summary -->
            <div class="mt-4 text-sm text-gray-600 text-center">
                Showing <?php echo e($evaluations->firstItem() ?? 0); ?> to <?php echo e($evaluations->lastItem() ?? 0); ?> of <?php echo e($evaluations->total()); ?> results
            </div>
        <?php endif; ?>
    </div>

    <!-- Evaluation Method Choice Modal -->
    <div 
        x-show="showChoiceModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="showChoiceModal = false"></div>
        
        <!-- Modal -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                @click.away="showChoiceModal = false"
            >
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-900">Choose Evaluation Method</h3>
                        <button 
                            @click="showChoiceModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Select how you would like to complete this evaluation</p>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Quick Input Option -->
                        <div class="group cursor-pointer">
                            <a 
                                :href="`/nbc/evaluation/${selectedEvaluationId}`"
                                class="block h-full"
                            >
                                <div class="h-full border-2 border-indigo-200 rounded-lg p-6 hover:border-indigo-500 hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-indigo-50 to-white">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="bg-indigo-100 rounded-full p-4 mb-4 group-hover:bg-indigo-200 transition-colors">
                                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">Quick Input</h4>
                                        <p class="text-sm text-gray-600 mb-4">
                                            Directly enter scores for the three main categories
                                        </p>
                                        <ul class="text-xs text-gray-500 space-y-1 text-left w-full">
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Fast and simple
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Single page form
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-indigo-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Ideal for straightforward evaluations
                                            </li>
                                        </ul>
                                        <div class="mt-6 w-full">
                                            <span class="block w-full px-4 py-2 bg-indigo-600 text-white rounded-lg text-center font-medium group-hover:bg-indigo-700 transition-colors">
                                                Use Quick Input
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Detailed Form Option -->
                        <div class="group cursor-pointer">
                            <a 
                                :href="`/nbc/educational-qualification/${selectedEvaluationId}`"
                                class="block h-full"
                            >
                                <div class="h-full border-2 border-blue-200 rounded-lg p-6 hover:border-blue-500 hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-blue-50 to-white">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="bg-blue-100 rounded-full p-4 mb-4 group-hover:bg-blue-200 transition-colors">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">Detailed Forms</h4>
                                        <p class="text-sm text-gray-600 mb-4">
                                            Complete comprehensive evaluation across three sections
                                        </p>
                                        <ul class="text-xs text-gray-500 space-y-1 text-left w-full">
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Educational Qualification
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Experience & Service
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Professional Development
                                            </li>
                                        </ul>
                                        <div class="mt-6 w-full">
                                            <span class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-center font-medium group-hover:bg-blue-700 transition-colors">
                                                Use Detailed Forms
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                    <p class="text-xs text-gray-500 text-center">
                        Both methods will save your evaluation to the same database. Choose the one that works best for you.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\nbc\dashboard.blade.php ENDPATH**/ ?>