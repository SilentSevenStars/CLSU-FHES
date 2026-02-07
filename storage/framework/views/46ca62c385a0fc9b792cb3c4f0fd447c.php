<div class="flex gap-6">
    <!-- Secondary Sidebar -->
    <aside class="w-64 bg-white rounded-lg shadow-sm p-4 h-fit">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>
        <nav class="space-y-1">
            <a href="<?php echo e(route('profile-view')); ?>" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('profile-view') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50'); ?>">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                View Profile
            </a>
            <a href="<?php echo e(route('profile')); ?>" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('profile') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50'); ?>">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Update Profile
            </a>
            <a href="<?php echo e(route('update-password')); ?>" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md <?php echo e(request()->routeIs('update-password') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50'); ?>">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Update Password
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Profile Information</h2>
                <p class="mt-1 text-sm text-gray-600">View your profile details</p>
            </div>

            <div class="p-6">
                <!--[if BLOCK]><![endif]--><?php if($applicant): ?>
                    <div class="space-y-6">
                        <!-- Name Section -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <p class="text-base text-gray-900"><?php echo e($fullName); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <p class="text-base text-gray-900"><?php echo e(Auth::user()->email); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                    <p class="text-base text-gray-900"><?php echo e($applicant->phone_number ?: 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Complete Address</label>
                                    <p class="text-base text-gray-900"><?php echo e($fullAddress ?: 'No address provided'); ?></p>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                                        <p class="text-base text-gray-900"><?php echo e($applicant->region ?: 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                        <p class="text-base text-gray-900"><?php echo e($applicant->province ?: 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">City/Municipality</label>
                                        <p class="text-base text-gray-900"><?php echo e($applicant->city ?: 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                                        <p class="text-base text-gray-900"><?php echo e($applicant->barangay ?: 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Street</label>
                                        <p class="text-base text-gray-900"><?php echo e($applicant->street ?: 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                        <p class="text-base text-gray-900"><?php echo e($applicant->postal_code ?: 'N/A'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No profile information</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by updating your profile.</p>
                        <div class="mt-6">
                            <a href="<?php echo e(route('profile')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                Update Profile
                            </a>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views/livewire/applicant/profile-view.blade.php ENDPATH**/ ?>