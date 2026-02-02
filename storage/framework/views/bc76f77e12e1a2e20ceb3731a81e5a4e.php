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
                        <h2 class="text-3xl font-bold text-white mb-2">Edit Application Review</h2>
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
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Position: <?php echo e($application->position->name ?? 'N/A'); ?></h3>
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
                                    <?php
                                    $reqPath = $application->requirements_file ?? null;
                                    $reqUrl = $reqPath ? Storage::url($reqPath) : null;
                                    $reqExt = $reqPath ? strtolower(pathinfo($reqPath, PATHINFO_EXTENSION)) : null;
                                    ?>

                                    <?php if(!$reqPath): ?>
                                    <div class="p-6 text-center text-gray-500">No document submitted</div>

                                    <?php elseif(in_array($reqExt, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                    <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                        <img src="<?php echo e($reqUrl); ?>" alt="Requirements File"
                                            class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>

                                    <?php else: ?>
                                    <div class="aspect-[3/2] bg-gray-100 rounded flex items-center justify-center">
                                        <a href="<?php echo e($reqUrl); ?>" target="_blank"
                                            class="inline-flex items-center px-4 py-3 bg-[#0D7A2F] text-white rounded-lg hover:bg-[#0a6025] transition">
                                            View Document
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="bg-white border-2 border-indigo-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-5">Update Application Review</h3>

                    <!-- Info Box -->
                    <div class="mb-4 px-4 py-3 bg-blue-50 border border-blue-300 text-blue-800 rounded-lg">
                        <p class="font-semibold mb-1">📝 Email Notification Rules:</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            <li>Changing interview details (date/room) for an <strong>approved</strong> application <strong>WILL send</strong> an update notification email</li>
                            <li>Changing status from <strong>Approved → Declined</strong> will send a decline notification email</li>
                            <li>Changing status from <strong>Declined → Approved</strong> will send an approval notification email</li>
                        </ul>
                    </div>

                    <form wire:submit.prevent="updateReview" class="space-y-5">
                        <?php echo csrf_field(); ?>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                Decision *
                            </label>

                            <select id="status" wire:model="status" wire:change="$refresh"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-[#0a6025] transition">
                                <option value="">Select Decision</option>
                                <option value="approve">Approve Application</option>
                                <option value="decline">Decline Application</option>
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

                        <?php if($status === 'approve'): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Interview Date -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Interview Date *</label>
                                <input type="date" wire:model="interview_date"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm">
                                <?php $__errorArgs = ['interview_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
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
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                        </div>

                        <!-- Show current values when status is approved -->
                        <?php if($originalStatus === 'approve' && $application->evaluation): ?>
                        <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800">
                                <strong>Current Interview Details:</strong> 
                                <?php echo e(date('F j, Y', strtotime($application->evaluation->interview_date))); ?> 
                                at <?php echo e($application->evaluation->interview_room); ?>

                            </p>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>

                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <a href="<?php echo e(route('admin.applicant')); ?>"
                                class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                                Cancel
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-3 bg-[#0D7A2F] text-white rounded-lg hover:bg-[#0a6025] transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="updateReview">Update Review</span>
                                <span wire:loading wire:target="updateReview">Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Review History -->
                <?php if($application->reviewed_at): ?>
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 border-2 border-gray-200">
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Review History</h4>
                    <div class="flex items-center text-sm text-gray-600">
                        <span>Last reviewed on <?php echo e($application->reviewed_at->format('F j, Y \a\t g:i A')); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\admin\applicant-edit.blade.php ENDPATH**/ ?>