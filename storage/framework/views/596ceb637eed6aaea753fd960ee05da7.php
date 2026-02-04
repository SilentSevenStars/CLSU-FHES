<div x-data="{ showModal: <?php if ((object) ('showModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'->value()); ?>')<?php echo e('showModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'); ?>')<?php endif; ?> }">
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Success Message -->
            <!--[if BLOCK]><![endif]--><?php if(session('success')): ?>
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg shadow animate-fadeIn">
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <?php
                    use Illuminate\Support\Facades\Storage;
                    ?>

                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0A6025] bg-clip-text text-transparent mb-2">
                            Available Positions
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Browse and apply for available job positions
                        </p>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-6 animate-fadeIn">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] sm:text-sm transition duration-150 ease-in-out shadow-sm"
                        placeholder="Search by position, department, college, or specialization..." />
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if($positions->isEmpty()): ?>

            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-xl p-12 text-center animate-fadeIn">
                <div class="max-w-md mx-auto">
                    <div
                        class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full p-6 w-24 h-24 mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Available Positions</h3>
                    <p class="text-gray-500">
                        <!--[if BLOCK]><![endif]--><?php if(!empty($search)): ?>
                        No positions match your search criteria. Try different keywords.
                        <?php else: ?>
                        There are no job positions available at the moment. Please check back later.
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </p>
                </div>
            </div>

            <?php else: ?>

            <!-- Positions Grid -->
            <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-8">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 animate-slideInLeft border-l-4 border-[#0A6025]"
                    style="animation-delay: <?php echo e($index * 0.1); ?>s;">
                    <div class="p-6">

                        <!-- Icon -->
                        <div
                            class="bg-gradient-to-br from-yellow-500 to-[#0A6025] rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300 w-16 h-16 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <!-- Job Title with Department -->
                        <h5
                            class="text-xl font-bold text-gray-800 mb-3 group-hover:text-[#0A6025] transition-colors duration-300 leading-tight">
                            <?php echo e($position->name); ?> - <?php echo e($position->department->name); ?>

                        </h5>

                        <!-- College -->
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="text-sm font-medium text-gray-600"><?php echo e($position->college->name); ?></p>
                        </div>

                        <!-- Date Range -->
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($position->start_date)->format('M d, Y')); ?> -
                                <?php echo e(\Carbon\Carbon::parse($position->end_date)->format('M d, Y')); ?>

                            </p>
                        </div>

                        <!-- View Details Button -->
                        <button wire:click="viewDetails(<?php echo e($position->id); ?>)"
                            class="block w-full text-center text-white bg-[#0A6025] hover:bg-[#0B712C] focus:ring-4 focus:ring-[#0A6025] 
                                            font-semibold rounded-lg text-sm px-4 py-3 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                            View Details
                        </button>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <!-- Background Overlay -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <!--[if BLOCK]><![endif]--><?php if($selectedPosition): ?>
                <!-- Modal Header (Fixed) -->
                <div class="bg-gradient-to-r from-[#0A6025] to-[#0B712C] px-6 py-4 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-white" id="modal-title">
                            <?php echo e($selectedPosition->name); ?>

                        </h3>
                        <button @click="$wire.closeModal()"
                            class="text-white hover:text-gray-200 transition-colors duration-200">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                    <div class="space-y-6">

                        <!-- Department & College -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">Department</h4>
                                <p class="text-lg font-medium text-gray-800"><?php echo e($selectedPosition->department->name); ?></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">College</h4>
                                <p class="text-lg font-medium text-gray-800"><?php echo e($selectedPosition->college->name); ?></p>
                            </div>
                        </div>

                        <!-- Status & Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">Status</h4>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        <?php echo e($selectedPosition->status === 'vacant' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                    <?php echo e(ucfirst($selectedPosition->status)); ?>

                                </span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">Start of Application</h4>
                                <p class="text-base font-medium text-gray-800">
                                    <?php echo e(\Carbon\Carbon::parse($selectedPosition->start_date)->format('M d, Y')); ?>

                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">End of Application</h4>
                                <p class="text-base font-medium text-gray-800">
                                    <?php echo e(\Carbon\Carbon::parse($selectedPosition->end_date)->format('M d, Y')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Specialization -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-semibold text-gray-500 mb-2">Specialization</h4>
                            <p class="text-base text-gray-800"><?php echo e($selectedPosition->specialization); ?></p>
                        </div>

                        <!-- Requirements Section -->
                        <div class="border-t pt-6">
                            <h4 class="text-xl font-bold text-gray-800 mb-4">Requirements</h4>

                            <div class="space-y-4">
                                <!-- Education -->
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-[#0A6025] mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <div>
                                        <h5 class="font-semibold text-gray-800">Education</h5>
                                        <p class="text-gray-600"><?php echo e($selectedPosition->education); ?></p>
                                    </div>
                                </div>

                                <!-- Experience -->
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-[#0A6025] mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <h5 class="font-semibold text-gray-800">Experience</h5>
                                        <p class="text-gray-600"><?php echo e($selectedPosition->experience); ?> years of relevant
                                            experience</p>
                                    </div>
                                </div>

                                <!-- Training -->
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-[#0A6025] mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h5 class="font-semibold text-gray-800">Training</h5>
                                        <p class="text-gray-600"><?php echo e($selectedPosition->training); ?> hours of training
                                            required</p>
                                    </div>
                                </div>

                                <!-- Eligibility -->
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-[#0A6025] mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <h5 class="font-semibold text-gray-800">Eligibility</h5>
                                        <p class="text-gray-600"><?php echo e($selectedPosition->eligibility); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal Footer (Fixed) -->
                <div class="bg-gray-50 px-6 py-4 sticky bottom-0 border-t border-gray-200">
                    <div class="flex items-center justify-end gap-3">
                        <button @click="$wire.closeModal()"
                            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A6025] transition-colors duration-200">
                            Close
                        </button>

                        <!--[if BLOCK]><![endif]--><?php if(in_array($selectedPosition->id, $applied)): ?>
                        <!--[if BLOCK]><![endif]--><?php if($this->canEditApplication($selectedPosition->id)): ?>
                        <!-- Edit Application Button -->
                        <a href="<?php echo e(route('edit-job-application', ['application_id' => $this->getApplicationId($selectedPosition->id)])); ?>"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            Edit Application
                        </a>
                        <?php else: ?>
                        <!-- Already Applied (Can't Edit) -->
                        <button disabled
                            class="px-6 py-2.5 text-sm font-semibold text-gray-500 bg-gray-300 rounded-lg cursor-not-allowed">
                            Application Submitted
                        </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php else: ?>
                        <!-- Apply Now Button -->
                        <a href="<?php echo e(route('job-application', ['position_id' => $selectedPosition->id])); ?>"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-[#0A6025] rounded-lg hover:bg-[#0B712C] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A6025] transition-all duration-200 shadow-md hover:shadow-lg">
                            Apply Now
                        </a>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }

        .animate-slideInLeft {
            animation: slideInLeft 0.5s ease-out;
        }
    </style>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views/livewire/applicant/apply-job.blade.php ENDPATH**/ ?>