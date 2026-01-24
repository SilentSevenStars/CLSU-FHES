<div class="flex gap-6">
    <!-- Secondary Sidebar -->
    <aside class="w-64 bg-white rounded-lg shadow-sm p-4 h-fit">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>
        <nav class="space-y-1">
            <a href="{{ route('panel.profile-view') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('panel.profile-view') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                View Profile
            </a>
            <a href="{{ route('panel.profile') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('panel.profile') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Update Profile
            </a>
            <a href="{{ route('panel.update-password') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('panel.update-password') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
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
                <div class="space-y-6">
                    <!-- Account Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <p class="text-base text-gray-900">{{ $panel->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <p class="text-base text-gray-900">{{ $panel->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <p class="text-base text-gray-900">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Panel Member
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                                <p class="text-base text-gray-900">{{ $panel->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Information -->
                    @if($panelInfo)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Panel Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                <p class="text-base text-gray-900">{{ $panelInfo->panel_position }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">College</label>
                                <p class="text-base text-gray-900">{{ $panelInfo->college }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <p class="text-base text-gray-900">{{ $panelInfo->department !== 'none' ? $panelInfo->department : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Account Status -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Verification</label>
                                <p class="text-base">
                                    @if($panel->email_verified_at)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            Not Verified
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                                <p class="text-base text-gray-900">{{ $panel->updated_at->format('F d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>