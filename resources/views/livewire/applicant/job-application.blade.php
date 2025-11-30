<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-yellow-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-4xl mx-auto">

            <!-- ========================= -->
            <!--   COUNTDOWN TIMER BLOCK   -->
            <!-- ========================= -->
            <div class="mb-8 p-4 bg-white shadow-md rounded-xl border-l-4 border-[#0A6025]"
                x-data="{
                    deadline: {{ $deadlineTimestamp }} * 1000,
                    now: Date.now(),
                    remaining: 0,
                    timer: null,
                    format(ms) {
                        let total = Math.floor(ms / 1000);
                        let days = Math.floor(total / 86400);
                        total %= 86400;
                        let hours = Math.floor(total / 3600);
                        total %= 3600;
                        let minutes = Math.floor(total / 60);
                        let seconds = total % 60;
                        return `${String(days).padStart(2,'0')} : ${String(hours).padStart(2,'0')} : ${String(minutes).padStart(2,'0')} : ${String(seconds).padStart(2,'0')}`;
                    },
                    init() {
                        this.timer = setInterval(() => {
                            this.now = Date.now();
                            this.remaining = this.deadline - this.now;
                        }, 1000);
                    }
                }"
            >
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Application Deadline Countdown
                </h2>
                <p class="mt-2 text-3xl font-extrabold text-[#0A6025]"
                    x-text="remaining > 0 ? format(remaining) : 'Closed'"></p>
            </div>

            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0A6025] bg-clip-text text-transparent mb-2">
                            Job Application Form
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Fill out the form to apply for the position
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">

                <!-- AUTO-SCROLL TO FIRST ERROR -->
                <form wire:submit.prevent="confirmSubmission"
                      x-data
                      x-on:scroll-to-error.window="document.querySelector('.input-error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });"
                      class="p-8 space-y-8">

                    @csrf

                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-yellow-500 to-[#0A6025] rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Personal Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="first_name"
                                    class="block w-full px-4 py-3 bg-gray-50 border 
                                           @error('first_name') input-error border-red-500 @else border-gray-300 @enderror 
                                           rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('first_name')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Middle Name (Optional)</label>
                                <input type="text" wire:model="middle_name"
                                    class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg">
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="last_name"
                                    class="block w-full px-4 py-3 bg-gray-50 border 
                                        @error('last_name') input-error border-red-500 @else border-gray-300 @enderror 
                                        rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('last_name')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Contact Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="phone_number"
                                    class="block w-full px-4 py-3 bg-gray-50 border
                                         @error('phone_number') input-error border-red-500 @else border-gray-300 @enderror
                                         rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('phone_number')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Address <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="address"
                                    class="block w-full px-4 py-3 bg-gray-50 border
                                         @error('address') input-error border-red-500 @else border-gray-300 @enderror
                                         rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('address')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Employment Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Present Position -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Present Position <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="present_position"
                                    class="block w-full px-4 py-3 bg-gray-50 border
                                          @error('present_position') input-error border-red-500 @else border-gray-300 @enderror
                                          rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('present_position')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Experience -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Years of Experience <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="experience" min="0"
                                    class="block w-full px-4 py-3 bg-gray-50 border
                                          @error('experience') input-error border-red-500 @else border-gray-300 @enderror
                                          rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('experience')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Education -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Education <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="education"
                                    class="block w-full px-4 py-3 bg-gray-50 border
                                          @error('education') input-error border-red-500 @else border-gray-300 @enderror
                                          rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('education')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Training -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Training <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="training"
                                    class="block w-full px-4 py-3 bg-gray-50 border
                                          @error('training') input-error border-red-500 @else border-gray-300 @enderror
                                          rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('training')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Eligibility -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Eligibility <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="eligibility"
                                    class="block w-full px-4 py-3 bg-gray-50 border
                                          @error('eligibility') input-error border-red-500 @else border-gray-300 @enderror
                                          rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('eligibility')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Other Involvement -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Other Involvement <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="other_involvement"
                                    class="block w-full px-4 py-3 bg-gray-50 border
                                          @error('other_involvement') input-error border-red-500 @else border-gray-300 @enderror
                                          rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('other_involvement')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Required Documents Section -->
                    <div class="pb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Required Documents</h3>
                        </div>

                        <div>
                            <p class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload Requirements (PDF only, max 2MB) <span class="text-red-500">*</span>
                            </p>

                            <label class="flex items-center justify-center w-full px-4 py-6 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#0A6025] cursor-pointer">

                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 48 48">
                                        <path stroke-linecap="round" 
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" />
                                    </svg>

                                    <p class="mt-2 text-sm text-gray-600">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500">PDF (MAX. 2MB)</p>
                                </div>

                                <input type="file" wire:model="requirements_file" accept="application/pdf" class="hidden">
                            </label>

                            @if ($requirements_file)
                                <p class="mt-2 text-sm text-green-600">
                                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    File selected: {{ $requirements_file->getClientOriginalName() }}
                                </p>
                            @endif

                            @error('requirements_file')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">

                        <!-- Cancel -->
                        <button type="button" onclick="history.back()"
                            class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
                            Cancel
                        </button>

                        <!-- Submit Button WITH LOADING -->
                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-[#0A6025] 
                                   hover:from-yellow-600 hover:to-[#0B712C] text-white font-semibold 
                                   rounded-lg shadow-lg transition-all duration-300">

                            <span wire:loading.remove>Submit Application</span>

                            <span wire:loading>
                                <svg class="animate-spin w-5 h-5 mx-auto" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </span>

                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- SweetAlert2 Integration (UNCHANGED) -->
    <div x-data 
        x-on:show-swal-confirm.window="
            Swal.fire({
                title: 'Submit Job Application?',
                text: 'Please confirm that all details are correct before submitting.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0A6025',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Submit',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.save();
                }
            });
        "
        x-on:swal:success.window="
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: $event.detail.message,
                confirmButtonColor: '#2563eb'
            }).then(() => {
                window.location.href = '{{ route('apply-job') }}';
            });
        ">
    </div>

</div>
