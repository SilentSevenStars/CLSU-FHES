<div class="min-h-screen bg-gray-50 py-8" 
    x-data="{
        showConfirmModal: false
    }"
    @confirm-submit.window="showConfirmModal = true">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Professional Development Form</h1>
                <p class="mt-1 text-sm text-gray-600">
                    <?php echo e($assignment->isEvaluator() ? 'Evaluator' : 'Verifier'); ?> Assessment - Part <?php echo e($currentPage); ?> of 3
                </p>
            </div>
            <button 
                wire:click="toggleApplicantModal"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150"
            >
                View Applicant Details
            </button>
        </div>

        <?php if(session()->has('message')): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                <p class="text-green-700"><?php echo e(session('message')); ?></p>
            </div>
        <?php endif; ?>

        <!-- Applicant Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Applicant Name</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($applicant->full_name); ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Position Applied</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($position->name); ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Department</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($position->department ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>

        <form>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">3.0 PROFESSIONAL DEVELOPMENT, ACHIEVEMENT & HONORS ... 90 pts.</h2>
                </div>

                <div class="p-6 space-y-6">
                    <?php if($currentPage == 1): ?>
                        <?php echo $__env->make('livewire.nbc.partials.professional-dev-part1', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($currentPage == 2): ?>
                        <?php echo $__env->make('livewire.nbc.partials.professional-dev-part2', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php else: ?>
                        <?php echo $__env->make('livewire.nbc.partials.professional-dev-part3', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?>

                    <!-- Cumulative Total Display (shown on all pages) -->
                    <div class="border-t-2 border-gray-300 pt-4 mt-6">
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <div class="col-span-8">
                                    <label class="block text-lg font-bold text-gray-900">
                                        <?php if($currentPage == 1): ?>
                                            PAGE 1 TOTAL (Section 3.1)
                                        <?php elseif($currentPage == 2): ?>
                                            CUMULATIVE TOTAL (Page 1 + Page 2)
                                        <?php else: ?>
                                            OVERALL TOTAL (All Pages Combined)
                                        <?php endif; ?>
                                    </label>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <?php if($currentPage == 1): ?>
                                            Maximum: 30 points
                                        <?php elseif($currentPage == 2): ?>
                                            Maximum: 60 points (30 + 10 + 20)
                                        <?php else: ?>
                                            Maximum: 90 points
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-span-4">
                                    <div class="px-4 py-3 bg-purple-100 border-2 border-purple-300 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-purple-900">
                                            <?php if($currentPage == 1): ?>
                                                <?php echo e(number_format($this->page1Total, 3)); ?>

                                            <?php elseif($currentPage == 2): ?>
                                                <?php echo e(number_format($this->page2Total, 3)); ?>

                                            <?php else: ?>
                                                <?php echo e(number_format($this->page3Total, 3)); ?>

                                            <?php endif; ?>
                                        </div>
                                        <div class="text-xs text-purple-700 mt-1">
                                            <?php if($currentPage == 3): ?>
                                                EP (Final Score)
                                            <?php else: ?>
                                                RS (Running Score)
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                    <button 
                        type="button"
                        wire:click="previous"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150"
                    >
                        ← Previous
                    </button>
                    
                    <?php if($currentPage < 3): ?>
                        <button 
                            type="button"
                            wire:click="next"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-150"
                        >
                            Next →
                        </button>
                    <?php else: ?>
                        <button 
                            type="button"
                            x-data
                            @click="$dispatch('confirm-submit')"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150"
                        >
                            Submit Evaluation
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Applicant Details Modal -->
    <?php if($showApplicantModal): ?>
        <div 
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            x-data="{ show: <?php if ((object) ('showApplicantModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showApplicantModal'->value()); ?>')<?php echo e('showApplicantModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showApplicantModal'); ?>')<?php endif; ?> }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
                <div class="flex items-center justify-between border-b pb-3 mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Applicant Details</h3>
                    <button 
                        wire:click="toggleApplicantModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Full Name</p>
                            <p class="mt-1 text-base text-gray-900"><?php echo e($applicant->full_name); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="mt-1 text-base text-gray-900"><?php echo e($applicant->user->email); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Position Applied</p>
                            <p class="mt-1 text-base text-gray-900"><?php echo e($position->name); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Department</p>
                            <p class="mt-1 text-base text-gray-900"><?php echo e($position->department ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button 
                        wire:click="toggleApplicantModal"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Confirm Submit Modal -->
    <div 
        x-show="showConfirmModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="showConfirmModal = false"></div>
        
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                class="relative bg-white rounded-lg shadow-xl max-w-md w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                @click.away="showConfirmModal = false"
            >
                <div class="pt-8 pb-4 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                        <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="px-6 pb-4 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Submit Evaluation?</h3>
                    <p class="text-gray-600">
                        Are you sure you want to submit this professional development evaluation? This action will mark the evaluation as complete.
                    </p>
                </div>
                
                <div class="px-6 pb-6 flex gap-3">
                    <button
                        @click="showConfirmModal = false"
                        class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="submit"
                        @click="showConfirmModal = false"
                        class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Yes, Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\nbc\professional-development-form.blade.php ENDPATH**/ ?>