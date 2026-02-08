<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-6 animate-fadeIn">
                <h1 class="text-4xl font-extrabold text-[#0A6025] mb-2">
                    Interview Evaluation
                </h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Evaluate the applicant's interview performance
                </p>
            </div>

            <!-- Applicant Details Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 hover:shadow-lg transition-shadow duration-200 cursor-pointer border-l-4 border-blue-600"
                wire:click="toggleApplicantModal">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Applicant Details</h3>
                            <p class="text-sm text-gray-600"><?php echo e($applicant->full_name); ?> - <?php echo e($position->name); ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-blue-600 font-semibold">
                        <span>View Here</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                <div class="bg-[#0A6025] p-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">I. Interview</h2>
                    </div>
                </div>

                <div class="p-8">

                    <!-- Instructions -->
                    <div
                        class="mb-8 bg-gradient-to-r from-[#0A6025]/10 to-green-50 border-l-4 border-[#0A6025] p-6 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-[#0A6025] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-bold text-lg mb-2 text-[#0A6025]">To the interviewer:</h3>
                                <p class="text-gray-700">
                                    A particular subject matter is given to the applicant. He may be opt to find to
                                    select a subject matter
                                    which is within his/her area of specialization and is allowed to prepare within
                                    period of five minutes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form wire:submit.prevent="confirmSubmission">
                        <?php if($currentPage == 1): ?>
                        <!-- Page 1: General Appearance through Alertness -->
                        <div class="space-y-8">
                            <!-- I. General Appearance -->
                            <div class="border-b pb-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-bold text-lg">I. General Appearance:</h4>
                                    <div class="flex gap-8 text-center">
                                        <span class="w-12 font-semibold">5</span>
                                        <span class="w-12 font-semibold">4</span>
                                        <span class="w-12 font-semibold">3</span>
                                        <span class="w-12 font-semibold">2</span>
                                        <span class="w-12 font-semibold">1</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-gray-700 flex-1">
                                        Consider the total effect of the applicant's appearance. How does his/her
                                        appearance impress you?
                                    </p>
                                    <div class="flex gap-8">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" wire:model="general_appearance" value="<?php echo e($i); ?>"
                                                class="w-6 h-6 cursor-pointer">
                                        </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['general_appearance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-sm mt-2"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- II. Manner Speaking -->
                            <div class="border-b pb-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-bold text-lg">II. Manner Speaking:</h4>
                                    <div class="flex gap-8 text-center">
                                        <span class="w-12 font-semibold">5</span>
                                        <span class="w-12 font-semibold">4</span>
                                        <span class="w-12 font-semibold">3</span>
                                        <span class="w-12 font-semibold">2</span>
                                        <span class="w-12 font-semibold">1</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-gray-700 flex-1">
                                        How well does he/she talk? Does he express himself clearly and adequately
                                    </p>
                                    <div class="flex gap-8">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" wire:model="manner_of_speaking" value="<?php echo e($i); ?>"
                                                class="w-6 h-6 cursor-pointer">
                                        </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['manner_of_speaking'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-sm mt-2"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- III. Physical Conditioning -->
                            <div class="border-b pb-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-bold text-lg">III. Physical Conditioning:</h4>
                                    <div class="flex gap-8 text-center">
                                        <span class="w-12 font-semibold">5</span>
                                        <span class="w-12 font-semibold">4</span>
                                        <span class="w-12 font-semibold">3</span>
                                        <span class="w-12 font-semibold">2</span>
                                        <span class="w-12 font-semibold">1</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-gray-700 flex-1">
                                        How physically energetic he/she is?
                                    </p>
                                    <div class="flex gap-8">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" wire:model="physical_conditions" value="<?php echo e($i); ?>"
                                                class="w-6 h-6 cursor-pointer">
                                        </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['physical_conditions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-sm mt-2"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- IV. Alertness -->
                            <div class="pb-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-bold text-lg">IV. Alertness:</h4>
                                    <div class="flex gap-8 text-center">
                                        <span class="w-12 font-semibold">5</span>
                                        <span class="w-12 font-semibold">4</span>
                                        <span class="w-12 font-semibold">3</span>
                                        <span class="w-12 font-semibold">2</span>
                                        <span class="w-12 font-semibold">1</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-start">
                                    <p class="text-gray-700 flex-1 pr-4">
                                        Consider the applicant's ability to comprehend your questions speedily and
                                        anticipate your thought.
                                        Has the capacity to transfer attention from one subject to another quickly? Is
                                        there a lag in his/her
                                        reaction to your discussion? How mentally alert is he/she?
                                    </p>
                                    <div class="flex gap-8 flex-shrink-0">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" wire:model="alertness" value="<?php echo e($i); ?>"
                                                class="w-6 h-6 cursor-pointer">
                                        </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['alertness'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-sm mt-2"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Navigation Buttons Page 1 -->
                        <div class="flex justify-center gap-4 mt-8">
                            <?php if(session()->has('error')): ?>
                            <div class="w-full text-center mb-4">
                                <span class="text-red-500 font-semibold"><?php echo e(session('error')); ?></span>
                            </div>
                            <?php endif; ?>
                            <button type="button" wire:click="nextPage"
                                class="bg-[#0A6025] hover:bg-[#0B712C] text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                Next →
                            </button>
                        </div>

                        <?php elseif($currentPage == 2): ?>
                        <!-- Page 2: Self Confidence through Maturity of Judgement -->
                        <div class="space-y-8">
                            <!-- V. Self Confidence -->
                            <div class="border-b pb-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-bold text-lg">V. Self Confidence:</h4>
                                    <div class="flex gap-8 text-center">
                                        <span class="w-12 font-semibold">5</span>
                                        <span class="w-12 font-semibold">4</span>
                                        <span class="w-12 font-semibold">3</span>
                                        <span class="w-12 font-semibold">2</span>
                                        <span class="w-12 font-semibold">1</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-gray-700 flex-1">
                                        How self assuring he/she? Is he/she wholesomely self confident and assured if
                                        does he/she see, uncertain of himself?
                                    </p>
                                    <div class="flex gap-8">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" wire:model="self_confidence" value="<?php echo e($i); ?>"
                                                class="w-6 h-6 cursor-pointer">
                                        </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['self_confidence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-sm mt-2"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- VI. Ability to Present Ideas -->
                            <div class="border-b pb-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-bold text-lg">VI. Ability to Present Ideas:</h4>
                                    <div class="flex gap-8 text-center">
                                        <span class="w-12 font-semibold">5</span>
                                        <span class="w-12 font-semibold">4</span>
                                        <span class="w-12 font-semibold">3</span>
                                        <span class="w-12 font-semibold">2</span>
                                        <span class="w-12 font-semibold">1</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-gray-700 flex-1">
                                        Does he/she present relevant, clear, and logical ideas?
                                    </p>
                                    <div class="flex gap-8">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" wire:model="ability_to_present_ideas" value="<?php echo e($i); ?>"
                                                class="w-6 h-6 cursor-pointer">
                                        </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['ability_to_present_ideas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-sm mt-2"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- VII. Maturity of Judgement -->
                            <div class="pb-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-bold text-lg">VII. Maturity of Judgement:</h4>
                                    <div class="flex gap-8 text-center">
                                        <span class="w-12 font-semibold">5</span>
                                        <span class="w-12 font-semibold">4</span>
                                        <span class="w-12 font-semibold">3</span>
                                        <span class="w-12 font-semibold">2</span>
                                        <span class="w-12 font-semibold">1</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-gray-700 flex-1">
                                        Could he/she judiculously act on a given situation? Does his/her judegement
                                        reflectanalytical vision?
                                    </p>
                                    <div class="flex gap-8">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" wire:model="maturity_of_judgement" value="<?php echo e($i); ?>"
                                                class="w-6 h-6 cursor-pointer">
                                        </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['maturity_of_judgement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-sm mt-2"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Navigation Buttons Page 2 -->
                        <div class="mt-8">
                            <?php if(session()->has('error')): ?>
                            <div class="w-full text-center mb-4">
                                <span class="text-red-500 font-semibold"><?php echo e(session('error')); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="flex justify-center gap-4">
                                <button type="button" wire:click="previousPage"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                    ← Return
                                </button>
                                <?php
                                $applicantPosition = $evaluation->jobApplication->position->name ?? null;
                                ?>
                                <button type="submit"
                                    class="bg-[#0A6025] hover:bg-[#0B712C] text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                    <?php if($applicantPosition === 'Instructor I'): ?>
                                    Next →
                                    <?php else: ?>
                                    Submit ✓
                                    <?php endif; ?>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Applicant Details Modal -->
    <?php if($showApplicantModal): ?>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        x-data="{ show: <?php if ((object) ('showApplicantModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showApplicantModal'->value()); ?>')<?php echo e('showApplicantModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showApplicantModal'); ?>')<?php endif; ?> }" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between border-b pb-3 mb-4">
                <h3 class="text-2xl font-bold text-gray-900">Applicant Details</h3>
                <button wire:click="toggleApplicantModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
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
                        <p class="text-sm font-medium text-gray-500">Phone Number</p>
                        <p class="mt-1 text-base text-gray-900"><?php echo e($applicant->phone_number ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Position Applied</p>
                        <p class="mt-1 text-base text-gray-900"><?php echo e($position->name); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Department</p>
                        <p class="mt-1 text-base text-gray-900"><?php echo e($position->department->name ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">College</p>
                        <p class="mt-1 text-base text-gray-900"><?php echo e($position->college->name ?? 'N/A'); ?></p>
                    </div>
                </div>

                <?php if($applicant->region || $applicant->city): ?>
                <div class="pt-4 border-t">
                    <p class="text-sm font-medium text-gray-500 mb-2">Address</p>
                    <p class="text-base text-gray-900">
                        <?php echo e(collect([$applicant->street, $applicant->barangay, $applicant->city, $applicant->province,
                        $applicant->region])->filter()->join(', ')); ?>

                    </p>
                </div>
                <?php endif; ?>

                <?php if($jobApplication->requirements_file): ?>
                <div class="pt-4 border-t">
                    <p class="text-sm font-medium text-gray-500 mb-2">Requirements File</p>
                    <button type="button" wire:click="$dispatch('open-pdf-viewer')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Requirements File
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <div class="flex justify-end mt-6 pt-4 border-t">
                <button wire:click="toggleApplicantModal"
                    class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150">
                    Close
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- PDF VIEWER MODAL -->
    <div x-data="{ 
        open: false,
        loading: false,
        pdfUrl: null,
        async openPdfViewer() {
            this.loading = true;
            this.open = true;
            try {
                const dataUrl = await window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('getFileDataUrl');
                if (dataUrl) {
                    this.pdfUrl = dataUrl;
                }
            } catch (error) {
                console.error('Error loading PDF:', error);
                alert('Error loading PDF file');
                this.open = false;
            } finally {
                this.loading = false;
            }
        }
    }" x-on:open-pdf-viewer.window="openPdfViewer()" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-hidden"
        style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-75" @click="open = false; pdfUrl = null;"></div>

        <!-- Modal Content -->
        <div class="relative w-full h-full flex items-center justify-center">
            <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-6xl h-screen flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Application Requirements</h3>
                    <button @click="open = false; pdfUrl = null;"
                        class="text-gray-400 hover:text-gray-600 transition p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- PDF Viewer -->
                <div class="flex-1 overflow-hidden bg-gray-100">
                    <div x-show="loading" class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                            </svg>
                            <p class="text-gray-600">Loading PDF...</p>
                        </div>
                    </div>
                    <iframe x-show="!loading && pdfUrl" :src="pdfUrl" class="w-full h-full" frameborder="0">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Handler -->
    <div x-data x-on:show-error.window="
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: $event.detail.message,
            confirmButtonColor: '#d33'
        });
    "></div>

    <!-- SweetAlert2 Integration -->
    <div x-data="{ 
        init() {
            window.addEventListener('show-swal-confirm', () => {
                Swal.fire({
                    title: 'Submit Interview Evaluation?',
                    text: 'Please confirm that all ratings are correct before submitting.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0A6025',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Submit'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('saveInterview');
                    }
                });
            });

            window.addEventListener('interview-saved', () => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Interview evaluation saved successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0A6025'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?php echo e(route('panel.dashboard')); ?>';
                    }
                });
            });
        }
    }">
    </div>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\panel\interview.blade.php ENDPATH**/ ?>