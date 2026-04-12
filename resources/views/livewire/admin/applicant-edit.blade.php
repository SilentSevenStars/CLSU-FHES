<div class="flex-1 p-6 overflow-y-auto bg-gray-50">
    <div class="max-w-6xl mx-auto">
        {{-- Flash Messages --}}
        @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800 leading-5 tracking-wide">{{ session('success') }}</p>
        </div>
        @endif

        @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-800 leading-5 tracking-wide">{{ session('error') }}</p>
        </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-[#0A6025] px-6 py-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Edit Application Review</h2>
                        <p class="text-indigo-100">Application ID: #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-lg text-sm font-semibold shadow-lg
                        {{ $application->status === 'approve' ? 'bg-green-500 text-white'  : '' }}
                        {{ $application->status === 'decline' ? 'bg-red-500 text-white'    : '' }}
                        {{ $application->status === 'pending' ? 'bg-yellow-500 text-white' : '' }}
                        {{ $application->status === 'hired'   ? 'bg-blue-500 text-white'   : '' }}">
                        {{ $application->status === 'approve' ? 'Approved' : '' }}
                        {{ $application->status === 'decline' ? 'Declined' : '' }}
                        {{ $application->status === 'pending' ? 'Pending'  : '' }}
                        {{ $application->status === 'hired'   ? 'Hired'    : '' }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Applicant + Documents grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal / Employment Information -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Applicant Information</h3>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Position: {{ $application->position->name ?? 'N/A' }}</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Full Name</span>
                                <span class="font-medium text-gray-900">{{ $application->applicant->first_name }} {{ $application->applicant->last_name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Email</span>
                                <span class="font-medium text-gray-900">{{ $application->applicant->user->email }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Contact Number</span>
                                <span class="font-medium text-gray-900">{{ $application->applicant->phone_number }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Address</span>
                                <span class="font-medium text-gray-900">{{ $application->applicant->address }}</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 mt-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Present Position</span>
                                    <span class="font-medium text-gray-900">{{ $application->present_position }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Education</span>
                                    <span class="font-medium text-gray-900">{{ $application->education }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Experience</span>
                                    <span class="font-medium text-gray-900">{{ $application->experience }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Training</span>
                                    <span class="font-medium text-gray-900">{{ $application->training }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Other Involvement</span>
                                    <span class="font-medium text-gray-900">{{ $application->other_involvement }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-5">Submitted Documents</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="space-y-3">
                                <h4 class="font-semibold text-gray-900">Requirement files</h4>
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    @if($application->requirements_file)
                                    <div class="aspect-[3/2] bg-gray-100 rounded flex items-center justify-center">
                                        <button type="button" wire:click="$dispatch('open-pdf-viewer')"
                                            class="inline-flex items-center px-4 py-3 bg-[#0D7A2F] text-white rounded-lg hover:bg-[#0a6025] transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Document
                                        </button>
                                    </div>
                                    @else
                                    <div class="p-6 text-center text-gray-500">No document submitted</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ══ Edit Form ══════════════════════════════════════════════ -->
                <div class="bg-white border-2 border-indigo-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-5">Update Application Review</h3>

                    <!-- Email rules info box -->
                    <div class="mb-4 px-4 py-3 bg-blue-50 border border-blue-300 text-blue-800 rounded-lg">
                        <p class="font-semibold mb-1">📝 Email Notification Rules:</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            <li>Changing interview details (date/room) for an <strong>approved</strong> application <strong>WILL send</strong> an update notification email</li>
                            <li>Changing status from <strong>Approved → Declined</strong> will send a decline notification email</li>
                            <li>Changing status from <strong>Declined → Approved</strong> will send an approval notification email</li>
                            <li>Adding an <strong>Additional Message</strong> or <strong>Attachments</strong> will always trigger an email notification</li>
                        </ul>
                    </div>

                    <div
                        x-data="{
                            quill: null,
                            currentStatus: '{{ $status }}',
                            initQuill() {
                                if (this.quill) return;
                                if (typeof Quill === 'undefined') {
                                    console.warn('Quill not loaded yet, retrying...');
                                    setTimeout(() => this.initQuill(), 100);
                                    return;
                                }
                                this.$nextTick(() => {
                                    const el = document.getElementById('quill-editor-edit');
                                    if (!el) return;
                                    this.quill = new Quill(el, {
                                        theme: 'snow',
                                        placeholder: 'Add a personal message to the applicant. This will appear in the notification email sent to them.',
                                        modules: {
                                            toolbar: [
                                                [{ header: [1, 2, 3, false] }],
                                                ['bold', 'italic', 'underline'],
                                                [{ list: 'ordered' }, { list: 'bullet' }],
                                                [{ align: [] }],
                                                ['clean']
                                            ]
                                        }
                                    });
                                });
                            },
                            handleStatusChange(val) {
                                this.currentStatus = val;
                                $wire.set('status', val, false);
                                if (val === 'approve' || val === 'decline') { this.initQuill(); }
                            },
                            handleSubmit() {
                                if (this.quill) {
                                    const isEmpty = this.quill.getText().trim().length === 0;
                                    $wire.set('admin_message', isEmpty ? '' : this.quill.root.innerHTML, false);
                                }
                                $wire.call('updateReview');
                            }
                        }"
                        x-init="initQuill()"
                    >
                        <div class="space-y-5">

                            <!-- Decision -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Decision <span class="text-red-500">*</span>
                                </label>
                                <select id="status"
                                    @change="handleStatusChange($event.target.value)"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-[#0a6025] transition">
                                    <option value="">Select Decision</option>
                                    <option value="approve" {{ $status === 'approve' ? 'selected' : '' }}>Approve Application</option>
                                    <option value="decline" {{ $status === 'decline' ? 'selected' : '' }}>Decline Application</option>
                                </select>
                                @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Interview fields — approve only -->
                            <div x-show="currentStatus === 'approve'" x-cloak>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Interview Date <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" wire:model="interview_date"
                                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm">
                                        @error('interview_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Interview Room <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" wire:model="interview_room"
                                            class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm"
                                            placeholder="e.g. CLSU Building Room 304">
                                        @error('interview_room') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                @if($originalStatus === 'approve' && $application->evaluation)
                                <div class="mt-3 px-4 py-3 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-800">
                                        <strong>Current Interview Details:</strong>
                                        {{ date('F j, Y', strtotime($application->evaluation->interview_date)) }}
                                        at {{ $application->evaluation->interview_room }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            <!-- Message + Attachments — approve or decline -->
                            <div x-show="currentStatus === 'approve' || currentStatus === 'decline'" x-cloak class="space-y-5">

                                <!-- Quill editor -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Additional Message
                                        <span class="font-normal text-gray-500">(Optional)</span>
                                    </label>
                                    <div wire:ignore>
                                        <div id="quill-editor-edit" style="min-height: 160px; background: white;"></div>
                                    </div>
                                    @error('admin_message') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                                    <p class="mt-1 text-xs text-gray-500">
                                        This message will be included in the email notification sent to the applicant.
                                    </p>
                                </div>

                                <!-- File Attachments -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-[#0D7A2F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                        Attachments
                                        <span class="font-normal text-gray-400 text-xs">(optional — PDF, Word, Excel, images, etc. Max 10 MB each)</span>
                                    </label>

                                    <!-- Drop zone -->
                                    <label for="edit-file-upload"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-green-300 rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition-colors duration-200">
                                        <div class="flex flex-col items-center justify-center py-4">
                                            <svg class="w-8 h-8 mb-2 text-[#0D7A2F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold text-[#0D7A2F]">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">Multiple files allowed — attached to the notification email</p>
                                        </div>
                                        <input id="edit-file-upload" type="file"
                                            wire:model="attachments"
                                            multiple class="hidden"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.ppt,.pptx,.txt,.zip">
                                    </label>

                                    <!-- Upload spinner -->
                                    <div wire:loading wire:target="attachments" class="mt-2 flex items-center gap-2 text-sm text-green-700">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Uploading files...
                                    </div>

                                    @error('attachments.*')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <!-- File list -->
                                    @if(!empty($attachments))
                                    <ul class="mt-3 space-y-2">
                                        @foreach($attachments as $index => $file)
                                        <li wire:key="attachment-edit-{{ $index }}" class="flex items-center justify-between bg-white border border-green-200 rounded-lg px-4 py-3 shadow-sm">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-9 h-9 flex-shrink-0 bg-[#0D7A2F] rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-xs font-bold leading-none">
                                                        {{ strtoupper(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)) }}
                                                    </span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $file->getClientOriginalName() }}</p>
                                                    <p class="text-xs text-gray-500">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                                                </div>
                                            </div>
                                            <button type="button"
                                                wire:click="removeAttachment({{ $index }})"
                                                class="ml-4 flex-shrink-0 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Remove">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <p class="mt-2 text-xs text-gray-500">
                                        {{ count($attachments) }} file(s) selected — will be physically attached to the notification email.
                                    </p>
                                    @endif
                                </div>

                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t">
                                <a href="{{ route('admin.applicant') }}"
                                    class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                                    Cancel
                                </a>
                                <button type="button" @click="handleSubmit()"
                                    wire:loading.attr="disabled"
                                    wire:target="updateReview,attachments"
                                    class="inline-flex items-center px-4 py-3 bg-[#0D7A2F] text-white rounded-lg hover:bg-[#0a6025] transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="updateReview">Update Review</span>
                                    <span wire:loading wire:target="updateReview" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Review History -->
                @if($application->reviewed_at)
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 border-2 border-gray-200">
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Review History</h4>
                    <div class="flex items-center text-sm text-gray-600">
                        <span>Last reviewed on {{ $application->reviewed_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- PDF VIEWER MODAL -->
    <div x-data="{
            open: false, loading: false, pdfUrl: null,
            async openPdfViewer() {
                this.loading = true; this.open = true;
                try {
                    const dataUrl = await @this.call('getFileDataUrl');
                    if (dataUrl) { this.pdfUrl = dataUrl; }
                } catch (error) {
                    console.error('Error loading PDF:', error);
                    alert('Error loading PDF file');
                    this.open = false;
                } finally { this.loading = false; }
            }
        }"
        x-on:open-pdf-viewer.window="openPdfViewer()"
        x-show="open" x-cloak
        class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
        <div class="absolute inset-0 bg-black bg-opacity-75" @click="open = false; pdfUrl = null;"></div>
        <div class="relative w-full h-full flex items-center justify-center">
            <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-6xl h-screen flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Application Requirements</h3>
                    <button @click="open = false; pdfUrl = null;" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-hidden">
                    <div x-show="loading" class="flex items-center justify-center h-full">
                        <svg class="animate-spin h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                        </svg>
                    </div>
                    <iframe x-show="!loading && pdfUrl" :src="pdfUrl" class="w-full h-full" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Handler -->
    <div x-data x-on:show-error.window="
        Swal.fire({ icon: 'error', title: 'Error', text: $event.detail.message, confirmButtonColor: '#d33' });
    "></div>

    <style>
        [x-cloak] { display: none !important; }
        .ql-container.ql-snow { border-bottom-left-radius: .5rem; border-bottom-right-radius: .5rem; border-color: #d1d5db; min-height: 160px; }
        .ql-toolbar.ql-snow  { border-top-left-radius: .5rem; border-top-right-radius: .5rem; border-color: #d1d5db; background-color: #f9fafb; }
    </style>
</div>