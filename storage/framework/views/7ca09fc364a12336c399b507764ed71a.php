<aside class="w-64 bg-white border-r border-gray-200 min-h-screen fixed left-0 top-0 pt-16 hidden md:block">
    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Profile Settings</h2>
        
        <nav class="space-y-2">
            <a href="<?php echo e(route('profile.show')); ?>" 
               class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('profile.show') ? 'bg-green-50 text-green-700 font-semibold' : ''); ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                View Profile
            </a>

            <a href="<?php echo e(route('profile.edit')); ?>" 
               class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('profile.edit') ? 'bg-green-50 text-green-700 font-semibold' : ''); ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Update Profile
            </a>

            <a href="<?php echo e(route('profile.password')); ?>" 
               class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('profile.password') ? 'bg-green-50 text-green-700 font-semibold' : ''); ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Change Password
            </a>
        </nav>

        <div class="mt-8 pt-6 border-t border-gray-200">
            <a href="<?php echo e(route(Auth::user()->role === 'admin' ? 'admin.dashboard' : (Auth::user()->role === 'panel' ? 'panel.dashboard' : (Auth::user()->role === 'nbc' ? 'nbc.dashboard' : 'dashboard')))); ?>" 
               class="flex items-center px-4 py-3 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar -->
<div x-data="{ open: false }" class="md:hidden">
    <!-- Mobile menu button -->
    <button @click="open = !open" class="fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="open" @click="open = false" class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>

    <!-- Mobile Sidebar -->
    <aside x-show="open" @click.away="open = false" 
           class="fixed left-0 top-0 w-64 bg-white h-full z-50 transform transition-transform duration-300 shadow-xl">
        <div class="p-6 pt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Profile Settings</h2>
            
            <nav class="space-y-2">
                <a href="<?php echo e(route('profile.show')); ?>" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('profile.show') ? 'bg-green-50 text-green-700 font-semibold' : ''); ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    View Profile
                </a>

                <a href="<?php echo e(route('profile.edit')); ?>" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('profile.edit') ? 'bg-green-50 text-green-700 font-semibold' : ''); ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Update Profile
                </a>

                <a href="<?php echo e(route('profile.password')); ?>" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors <?php echo e(request()->routeIs('profile.password') ? 'bg-green-50 text-green-700 font-semibold' : ''); ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    Change Password
                </a>
            </nav>
        </div>
    </aside>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\components\profile-sidebar.blade.php ENDPATH**/ ?>