<div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-3xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-1">My Notifications</h2>
                <p class="text-gray-600">View all your notifications and messages</p>
            </div>
            <button
                wire:click="markAllAsRead"
                class="px-4 py-2 bg-[#1E7F3E] hover:bg-[#156B2D] text-white rounded-lg transition duration-200 font-medium"
            >
                Mark All as Read
            </button>
        </div>

    <?php if(session()->has('success')): ?>
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

        <!-- Filter Tabs -->
        <div class="mb-6 bg-white rounded-xl shadow overflow-hidden">
        <div class="flex border-b">
            <button 
                wire:click="$set('filter', 'all')"
             class="px-6 py-3 text-sm font-medium transition duration-200 <?php echo e($filter === 'all' ? 'text-[#1E7F3E] border-b-2 border-[#1E7F3E]' : 'text-gray-500 hover:text-gray-700'); ?>"
            >
                All Notifications
            </button>
            <button 
                wire:click="$set('filter', 'unread')"
                class="px-6 py-3 text-sm font-medium transition duration-200 <?php echo e($filter === 'unread' ? 'text-[#1E7F3E] border-b-2 border-[#1E7F3E]' : 'text-gray-500 hover:text-gray-700'); ?>"
            >
                Unread
            </button>
            <button 
                wire:click="$set('filter', 'read')"
                class="px-6 py-3 text-sm font-medium transition duration-200 <?php echo e($filter === 'read' ? 'text-[#1E7F3E] border-b-2 border-[#1E7F3E]' : 'text-gray-500 hover:text-gray-700'); ?>"
            >
                Read
            </button>
        </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow hover:shadow-md transition duration-200 <?php echo e(!$notification->is_read ? 'border-l-4 border-[#1E7F3E]' : ''); ?>">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <?php if(!$notification->is_read): ?>
                                    <span class="inline-block w-2 h-2 bg-[#1E7F3E] rounded-full mr-2"></span>
                                <?php endif; ?>
                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($notification->subject); ?></h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                <?php echo e($notification->created_at->format('F d, Y h:i A')); ?>

                            </p>
                            <div class="text-gray-700 line-clamp-2">
                                <?php echo Str::limit(strip_tags($notification->message), 150); ?>

                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button 
                                wire:click="viewNotification(<?php echo e($notification->id); ?>)"
                                class="text-[#1E7F3E] hover:text-[#156B2D]"
                                title="View"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <?php if(!$notification->is_read): ?>
                                <button 
                                    wire:click="markAsRead(<?php echo e($notification->id); ?>)"
                                    class="text-green-600 hover:text-green-800"
                                    title="Mark as Read"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            <?php else: ?>
                                <button 
                                    wire:click="markAsUnread(<?php echo e($notification->id); ?>)"
                                    class="text-yellow-600 hover:text-yellow-800"
                                    title="Mark as Unread"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            <?php endif; ?>
                            <button 
                                wire:click="deleteNotification(<?php echo e($notification->id); ?>)"
                                wire:confirm="Are you sure you want to delete this notification?"
                                class="text-red-600 hover:text-red-800"
                                title="Delete"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have any notifications yet.</p>
            </div>
        <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-6 bg-white rounded-xl shadow p-4">
            <?php echo e($notifications->links()); ?>

        </div>

        <!-- View Notification Modal -->
        <?php if($selectedNotification): ?>
            <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$wire.closeNotification()"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                        <div class="bg-[#1E7F3E] px-6 py-4 flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-white"><?php echo e($selectedNotification->subject); ?></h3>
                            <button wire:click="closeNotification" class="text-white/80 hover:text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="bg-white px-6 py-5">
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">
                                    <strong>Date:</strong> <?php echo e($selectedNotification->created_at->format('F d, Y h:i A')); ?>

                                </p>
                                <?php if($selectedNotification->email_sent): ?>
                                    <p class="text-sm text-green-700">
                                        <strong>Email Sent:</strong> <?php echo e($selectedNotification->email_sent_at?->format('F d, Y h:i A') ?? 'N/A'); ?>

                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <div class="prose max-w-none">
                                    <?php echo $selectedNotification->message; ?>

                                </div>
                            </div>

                <?php if($selectedNotification->attachments && count($selectedNotification->attachments) > 0): ?>
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Attachments</h4>
                        <div class="space-y-2">
                            <?php $__currentLoopData = $selectedNotification->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700"><?php echo e(basename($attachment)); ?></span>
                                    </div>
                                    <a 
                                        href="<?php echo e(Storage::url($attachment)); ?>"
                                        download
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        Download
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                            <div class="flex justify-end pt-4 border-t">
                                <button 
                                    wire:click="closeNotification"
                                    class="px-6 py-2 bg-[#1E7F3E] hover:bg-[#156B2D] text-white font-semibold rounded-lg transition duration-200"
                                >
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\applicant\applicant-notification.blade.php ENDPATH**/ ?>