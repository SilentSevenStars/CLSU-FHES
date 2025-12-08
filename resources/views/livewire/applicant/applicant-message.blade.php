<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Message</h2>
            <p class="text-gray-600">{{ $notification->subject }}</p>
        </div>
        <button 
            wire:click="back"
            class="text-blue-600 hover:text-blue-800 font-medium flex items-center"
        >
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Notifications
        </button>
    </div>

    <!-- Message Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="text-xl font-semibold text-gray-900">{{ $notification->subject }}</h3>
            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                <span>
                    <strong>Date:</strong> {{ $notification->created_at->format('F d, Y h:i A') }}
                </span>
                @if($notification->email_sent)
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                        Email Delivered
                    </span>
                @endif
                @if($notification->is_read)
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                        Read
                    </span>
                @endif
            </div>
        </div>

        <!-- Message Body -->
        <div class="p-6">
            <div class="prose max-w-none">
                {!! $notification->message !!}
            </div>
        </div>

        <!-- Attachments -->
        @if($notification->attachments && count($notification->attachments) > 0)
            <div class="px-6 pb-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    Attachments ({{ count($notification->attachments) }})
                </h4>
                <div class="space-y-2">
                    @foreach($notification->attachments as $attachment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">{{ basename($attachment) }}</span>
                            </div>
                            <a 
                                href="{{ Storage::url($attachment) }}"
                                download
                                class="flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t flex justify-between items-center">
            <p class="text-sm text-gray-600">
                @if($notification->is_read)
                    Read on {{ $notification->read_at->format('F d, Y h:i A') }}
                @else
                    Marked as read just now
                @endif
            </p>
            <button 
                wire:click="back"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200"
            >
                Back to Notifications
            </button>
        </div>
    </div>
</div>