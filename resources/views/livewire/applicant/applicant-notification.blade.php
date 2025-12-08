<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">My Notifications</h2>
            <p class="text-gray-600">View all your notifications and messages</p>
        </div>
        <button 
            wire:click="markAllAsRead"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200"
        >
            Mark All as Read
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 bg-white rounded-lg shadow">
        <div class="flex border-b">
            <button 
                wire:click="$set('filter', 'all')"
                class="px-6 py-3 text-sm font-medium transition duration-200 {{ $filter === 'all' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}"
            >
                All Notifications
            </button>
            <button 
                wire:click="$set('filter', 'unread')"
                class="px-6 py-3 text-sm font-medium transition duration-200 {{ $filter === 'unread' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}"
            >
                Unread
            </button>
            <button 
                wire:click="$set('filter', 'read')"
                class="px-6 py-3 text-sm font-medium transition duration-200 {{ $filter === 'read' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700' }}"
            >
                Read
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-lg shadow hover:shadow-md transition duration-200 {{ !$notification->is_read ? 'border-l-4 border-blue-500' : '' }}">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                @if(!$notification->is_read)
                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                @endif
                                <h3 class="text-lg font-semibold text-gray-900">{{ $notification->subject }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                {{ $notification->created_at->format('F d, Y h:i A') }}
                            </p>
                            <div class="text-gray-700 line-clamp-2">
                                {!! Str::limit(strip_tags($notification->message), 150) !!}
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button 
                                wire:click="viewNotification({{ $notification->id }})"
                                class="text-blue-600 hover:text-blue-800"
                                title="View"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            @if(!$notification->is_read)
                                <button 
                                    wire:click="markAsRead({{ $notification->id }})"
                                    class="text-green-600 hover:text-green-800"
                                    title="Mark as Read"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            @else
                                <button 
                                    wire:click="markAsUnread({{ $notification->id }})"
                                    class="text-yellow-600 hover:text-yellow-800"
                                    title="Mark as Unread"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            @endif
                            <button 
                                wire:click="deleteNotification({{ $notification->id }})"
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
        @empty
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have any notifications yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>

    <!-- View Notification Modal -->
    @if($selectedNotification)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white mb-10">
                <div class="flex justify-between items-center mb-4 pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $selectedNotification->subject }}</h3>
                    <button wire:click="closeNotification" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">
                        <strong>Date:</strong> {{ $selectedNotification->created_at->format('F d, Y h:i A') }}
                    </p>
                    @if($selectedNotification->email_sent)
                        <p class="text-sm text-green-600">
                            <strong>Email Sent:</strong> {{ $selectedNotification->email_sent_at->format('F d, Y h:i A') }}
                        </p>
                    @endif
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="prose max-w-none">
                        {!! $selectedNotification->message !!}
                    </div>
                </div>

                @if($selectedNotification->attachments && count($selectedNotification->attachments) > 0)
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Attachments</h4>
                        <div class="space-y-2">
                            @foreach($selectedNotification->attachments as $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ basename($attachment) }}</span>
                                    </div>
                                    <a 
                                        href="{{ Storage::url($attachment) }}"
                                        download
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex justify-end pt-4 border-t">
                    <button 
                        wire:click="closeNotification"
                        class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition duration-200"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>