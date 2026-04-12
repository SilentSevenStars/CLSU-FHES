<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Send Message
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Compose and send message to selected applicants
                        </p>
                    </div>
                </div>
            </div>

            @if (session()->has('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.2s;">

                <!-- Card header -->
                <div class="bg-[#0a6025] p-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <i class="fa-solid fa-envelope text-white text-lg"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Compose Message</h2>
                    </div>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="send">

                        <!-- Recipients -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Recipients
                            </label>
                            <div class="flex flex-wrap gap-2 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                @foreach($applicants as $applicant)
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $applicant->full_name }}
                                        <span class="ml-2 text-xs text-emerald-600">({{ $applicant->user->email }})</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-6">
                            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10m-7 4h7"></path>
                                </svg>
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="subject"
                                wire:model="subject"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent shadow-sm transition duration-200 @error('subject') border-red-500 @enderror"
                                placeholder="Enter message subject"
                            >
                            @error('subject')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Message — Quill Editor -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Message <span class="text-red-500">*</span>
                            </label>
                            <div wire:ignore class="border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                                <div id="quill-editor" style="min-height: 300px; background: white;"></div>
                            </div>
                            @error('message')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- ── File Attachments ──────────────────────────── -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                Attachments
                                <span class="font-normal text-gray-400 text-xs">(optional — PDF, Word, Excel, images, etc. Max 10 MB each)</span>
                            </label>

                            <!-- Drop zone -->
                            <label for="file-upload"
                                class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-green-300 rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition-colors duration-200">
                                <div class="flex flex-col items-center justify-center py-4">
                                    <svg class="w-9 h-9 mb-2 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold text-[#0a6025]">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">Multiple files allowed</p>
                                </div>
                                <input
                                    id="file-upload"
                                    type="file"
                                    wire:model="attachments"
                                    multiple
                                    class="hidden"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.ppt,.pptx,.txt,.zip"
                                >
                            </label>

                            <!-- Upload progress spinner -->
                            <div wire:loading wire:target="attachments" class="mt-2 flex items-center gap-2 text-sm text-green-700">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading files...
                            </div>

                            @error('attachments.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- File list -->
                            @if(!empty($attachments))
                                <ul class="mt-4 space-y-2">
                                    @foreach($attachments as $index => $file)
                                    <li class="flex items-center justify-between bg-white border border-green-200 rounded-lg px-4 py-3 shadow-sm">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <!-- Extension badge -->
                                            <div class="w-9 h-9 flex-shrink-0 bg-[#0a6025] rounded-lg flex items-center justify-center">
                                                <span class="text-white text-xs font-bold leading-none">
                                                    {{ strtoupper(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)) }}
                                                </span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-800 truncate">{{ $file->getClientOriginalName() }}</p>
                                                <p class="text-xs text-gray-500">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            wire:click="removeAttachment({{ $index }})"
                                            class="ml-4 flex-shrink-0 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Remove"
                                        >
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>

                                <!-- Summary badge -->
                                <p class="mt-2 text-xs text-gray-500">
                                    {{ count($attachments) }} file(s) selected —
                                    files will be physically attached to the email.
                                </p>
                            @endif
                        </div>
                        <!-- ── End Attachments ──────────────────────────── -->

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button
                                type="button"
                                wire:click="cancel"
                                class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium shadow-sm hover:shadow"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-2.5 bg-[#0a6025] hover:bg-green-700 text-white font-semibold rounded-lg transition duration-200 flex items-center shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="send,attachments"
                            >
                                <span wire:loading.remove wire:target="send" class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Send Message
                                </span>
                                <span wire:loading wire:target="send" class="flex items-center">
                                    <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            if (document.getElementById('quill-editor')) {
                var quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Compose your message...'
                });

                quill.on('text-change', function () {
                    @this.set('message', quill.root.innerHTML);
                });
            }
        });
    </script>
</div>