<div class="flex-1 p-6 overflow-y-auto bg-gray-50">
    <div class="max-w-6xl mx-auto">
        
        <?php if(session()->has('success')): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800 leading-5 tracking-wide"><?php echo e(session('success')); ?></p>
        </div>
        <?php endif; ?>

        <?php if(session()->has('error')): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-800 leading-5 tracking-wide"><?php echo e(session('error')); ?></p>
        </div>
        <?php endif; ?>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-[#0A6025] px-6 py-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Applicant Review</h2>
                        <p class="text-indigo-100">Application ID: #<?php echo e(str_pad($application->id, 6, '0', STR_PAD_LEFT)); ?></p>
                    </div>
                    <span class="px-4 py-2 rounded-lg text-sm font-semibold shadow-lg
                        <?php echo e($application->status === 'approve' ? 'bg-green-500 text-white' : ''); ?>

                        <?php echo e($application->status === 'decline' ? 'bg-red-500 text-white' : ''); ?>

                        <?php echo e($application->status === 'pending' ? 'bg-yellow-500 text-white' : ''); ?>

                        <?php echo e($application->status === 'hired' ? 'bg-blue-500 text-white' : ''); ?>">
                        <?php echo e($application->status === 'approve' ? 'Approved' : ''); ?>

                        <?php echo e($application->status === 'decline' ? 'Declined' : ''); ?>

                        <?php echo e($application->status === 'pending' ? 'Pending' : ''); ?>

                        <?php echo e($application->status === 'hired' ? 'Hired' : ''); ?>

                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Applicant Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Applicant Information</h3>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Position: <?php echo e($application->position->title ??
                            'N/A'); ?></h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Full Name</span>
                                <span class="font-medium text-gray-900">
                                    <?php echo e($application->applicant->first_name); ?> <?php echo e($application->applicant->last_name); ?>

                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Email</span>
                                <span class="font-medium text-gray-900">
                                    <?php echo e($application->applicant->user->email); ?>

                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Contact Number</span>
                                <span class="font-medium text-gray-900">
                                    <?php echo e($application->applicant->phone_number); ?>

                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Address</span>
                                <span class="font-medium text-gray-900"><?php echo e($application->applicant->address); ?></span>
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 mt-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Present Position</span>
                                    <span class="font-medium text-gray-900">
                                        <?php echo e($application->present_position); ?>

                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Education</span>
                                    <span class="font-medium text-gray-900">
                                        <?php echo e($application->education); ?>

                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Experience</span>
                                    <span class="font-medium text-gray-900">
                                        <?php echo e($application->experience); ?>

                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Training</span>
                                    <span class="font-medium text-gray-900">
                                        <?php echo e($application->training); ?>

                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Other Involvement</span>
                                    <span class="font-medium text-gray-900">
                                        <?php echo e($application->other_involvement); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-5">Submitted Documents</h3>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Requirements File -->
                            <div class="space-y-3">
                                <h4 class="font-semibold text-gray-900">Requirement files</h4>
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    <?php if($application->requirements_file): ?>
                                    <div class="aspect-[3/2] bg-gray-100 rounded flex items-center justify-center">
                                        <button type="button" wire:click="$dispatch('open-pdf-viewer')"
                                            class="inline-flex items-center px-4 py-3 bg-[#0D7A2F] text-white rounded-lg hover:bg-[#0a6025] transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Document
                                        </button>
                                    </div>
                                    <?php else: ?>
                                    <div class="p-6 text-center text-gray-500">No document submitted</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if($application->status === 'pending'): ?>
                <!-- Review Form -->
                <div class="bg-white border-2 border-indigo-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-5">Review Application</h3>

                    <?php if($isWithinApplicationPeriod): ?>
                    <p class="mb-4 px-4 py-3 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg">
                        The application period is still active (<?php echo e($application->position->start_date); ?>

                        to <?php echo e($application->position->end_date); ?>).<br>
                        You can only review applicants after the deadline.
                    </p>
                    <?php endif; ?>

                    <form wire:submit.prevent="submitReview" class="space-y-5">
                        <?php echo csrf_field(); ?>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                Decision *
                            </label>

                            <select id="status" wire:model="status" wire:change="$refresh"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-[#0a6025] transition"
                                <?php echo e($isWithinApplicationPeriod ? 'disabled' : ''); ?>>

                                <option value="">Select Decision</option>

                                <?php if(!$isWithinApplicationPeriod): ?>
                                <option value="approve">Approve Application</option>
                                <option value="decline">Decline Application</option>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <?php if($status === 'approve' && !$isWithinApplicationPeriod): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Interview Date -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Interview Date *</label>
                                <input type="date" wire:model="interview_date" min="<?php echo e(date('Y-m-d')); ?>"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm">
                                <?php $__errorArgs = ['interview_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Interview Room -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Interview Room *</label>
                                <input type="text" wire:model="interview_room"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm"
                                    placeholder="e.g. CLSU Building Room 304">
                                <?php $__errorArgs = ['interview_room'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                        </div>
                        <?php endif; ?>

                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <a href="<?php echo e(route('admin.applicant')); ?>"
                                class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                                Back to List
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-3 bg-[#0D7A2F] text-white rounded-lg hover:bg-[#0a6025] transition disabled:opacity-50 disabled:cursor-not-allowed"
                                <?php echo e($isWithinApplicationPeriod ? 'disabled' : ''); ?>>
                                <span wire:loading.remove wire:target="submitReview">Submit Review</span>
                                <span wire:loading wire:target="submitReview">Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <!-- Review Details -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 border-2 border-gray-200">
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Review Details</h4>

                    <?php if($application->status === 'approve' && $application->evaluation): ?>
                    <div class="bg-white rounded-lg p-4 mb-3 border border-green-200">
                        <p class="text-sm font-medium text-gray-700 mb-3">Interview Schedule:</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-semibold text-gray-900">
                                    <?php echo e(date('F j, Y', strtotime($application->evaluation->interview_date))); ?>

                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Location:</span>
                                <span class="font-semibold text-gray-900">
                                    <?php echo e($application->evaluation->interview_room); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($application->admin_notes): ?>
                    <div class="bg-white rounded-lg p-4 mb-3 border border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-1">Admin Notes:</p>
                        <p class="text-gray-900"><?php echo e($application->admin_notes); ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="flex items-center text-sm text-gray-600">
                        <span>Reviewed on <?php echo e($application->reviewed_at->format('F j, Y')); ?></span>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <a href="<?php echo e(route('admin.applicant')); ?>"
                        class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                        Back to List
                    </a>

                    <?php if(in_array($application->status, ['approve', 'decline'])): ?>
                    <a href="<?php echo e(route('admin.applicant.edit', $application->id)); ?>"
                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Review
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

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
    }"
    x-on:open-pdf-viewer.window="openPdfViewer()"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-hidden"
    style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-75" @click="open = false; pdfUrl = null;"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full h-full flex items-center justify-center">
            <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-6xl h-screen flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Application Requirements</h3>
                    <button @click="open = false; pdfUrl = null;" 
                        class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- PDF Viewer -->
                <div class="flex-1 overflow-hidden">
                    <div x-show="loading" class="flex items-center justify-center h-full">
                        <svg class="animate-spin h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                        </svg>
                    </div>
                    <iframe x-show="!loading && pdfUrl" 
                        :src="pdfUrl" 
                        class="w-full h-full"
                        frameborder="0">
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
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\admin\applicant-show.blade.php ENDPATH**/ ?>