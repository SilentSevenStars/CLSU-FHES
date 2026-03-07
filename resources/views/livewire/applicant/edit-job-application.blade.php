@php use Illuminate\Support\Facades\Storage; @endphp

<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-yellow-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-4xl mx-auto">

            <!-- COUNTDOWN TIMER -->
            <div class="mb-8 p-6 bg-white shadow-md rounded-xl border-l-4 border-[#0A6025]" x-data="{
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
                }">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Application Deadline Countdown
                </h2>
                <p class="mt-2 text-4xl font-extrabold text-[#0A6025]"
                    x-text="remaining > 0 ? format(remaining) : 'Closed'"></p>
            </div>

            <!-- HEADER -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-5xl font-extrabold bg-[#0A6025] bg-clip-text text-transparent mb-2">
                            Edit Job Application
                        </h1>
                        <p class="text-lg text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Update your application details before the deadline
                        </p>
                    </div>
                </div>
            </div>

            <!-- FORM CARD -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                <form wire:submit.prevent="confirmSubmission" x-data
                    x-on:scroll-to-error.window="document.querySelector('.input-error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });"
                    class="p-8 space-y-8">
                    @csrf

                    <!-- PERSONAL INFORMATION SECTION -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-yellow-500 to-[#0A6025] rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Personal Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="first_name"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('first_name') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('first_name')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Middle Name (Optional)</label>
                                <input type="text" wire:model="middle_name"
                                    class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="last_name"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('last_name') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('last_name')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Suffix (Optional)</label>
                                <select wire:model="suffix"
                                    class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                    <option value="">Select Suffix</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                    <option value="V">V</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" wire:model.live="phone_number" placeholder="09XXXXXXXXX"
                                        maxlength="11" pattern="[0-9]*" inputmode="numeric"
                                        class="block w-full px-4 py-3 bg-gray-50 border @error('phone_number') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]"
                                        x-data
                                        x-on:keypress="if (!/[0-9]/.test(String.fromCharCode($event.keyCode))) $event.preventDefault()"
                                        x-on:paste.prevent="
                                            let paste = ($event.clipboardData || window.clipboardData).getData('text');
                                            paste = paste.replace(/[^0-9]/g, '');
                                            $event.target.value = paste.substring(0, 11);
                                            $event.target.dispatchEvent(new Event('input'));
                                        ">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Format: 09XXXXXXXXX (11 digits starting with 09)</p>
                                @error('phone_number')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- ADDRESS INFORMATION SECTION -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Address Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Region <span class="text-red-500">*</span></label>
                                <select wire:model.live="region"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('region') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                    <option value="">Select Region</option>
                                    @foreach($regions as $reg)
                                        <option value="{{ $reg['name'] }}">{{ $reg['regionName'] ?? $reg['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('region')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Province <span class="text-red-500">*</span></label>
                                <select wire:model.live="province"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('province') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]"
                                    @if(!$region) disabled @endif>
                                    <option value="">Select Province</option>
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov['name'] }}">{{ $prov['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('province')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">City/Municipality <span class="text-red-500">*</span></label>
                                <select wire:model.live="city"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('city') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]"
                                    @if(!$province) disabled @endif>
                                    <option value="">Select City/Municipality</option>
                                    @foreach($cities as $ct)
                                        <option value="{{ $ct['name'] }}">{{ $ct['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('city')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Barangay <span class="text-red-500">*</span></label>
                                <select wire:model="barangay"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('barangay') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]"
                                    @if(!$city) disabled @endif>
                                    <option value="">Select Barangay</option>
                                    @foreach($barangays as $brgy)
                                        <option value="{{ $brgy['name'] }}">{{ $brgy['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('barangay')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Street/Building <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="street" placeholder="House No., Street Name, Building"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('street') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('street')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Postal Code <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="postal_code" placeholder="e.g., 1234"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('postal_code') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('postal_code')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- EMPLOYMENT INFORMATION SECTION -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Employment Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Present Position <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="present_position"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('present_position') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('present_position')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Years of Experience <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="experience" min="0"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('experience') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('experience')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Education <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="education" list="educationOptions"
                                    placeholder="e.g., Master of Science in Information Technology"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('education') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                <datalist id="educationOptions">
                                    @foreach($educationOptions as $option)
                                        <option value="{{ $option }}"></option>
                                    @endforeach
                                </datalist>
                                @error('education')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Training (Hours) <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="training" min="0"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('training') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('training')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Eligibility <span class="text-red-500">*</span></label>
                                @if($eligibilityIsFixed)
                                    <div class="relative">
                                        <input type="text" value="None Required" disabled
                                            class="block w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-500 cursor-not-allowed">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="hidden" wire:model="eligibility">
                                    <p class="mt-1 text-xs text-gray-400 italic flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                        Automatically set to "None Required" for this position.
                                    </p>
                                @else
                                    <input type="text" wire:model="eligibility"
                                        class="block w-full px-4 py-3 bg-gray-50 border @error('eligibility') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @endif
                                @error('eligibility')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Other Involvement <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="other_involvement"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('other_involvement') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025]">
                                @error('other_involvement')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- REQUIRED DOCUMENTS SECTION -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Required Documents</h3>
                        </div>

                        <div>
                            <p class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload Requirements (PDF only, max 100MB)
                                <span class="text-gray-500 font-normal">(Leave empty to keep current file)</span>
                            </p>

                            @if($existing_file_path && !$requirements_file)
                            <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <span class="text-sm font-medium text-blue-700">Current file uploaded (encrypted)</span>
                                            <p class="text-xs text-blue-600">Upload a new file to replace this one</p>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="$dispatch('open-pdf-viewer')"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View File
                                    </button>
                                </div>
                            </div>
                            @endif

                            <div x-data="{
                                    isDragging: false,
                                    isUploading: false,
                                    handleDragOver(e) { e.preventDefault(); this.isDragging = true; },
                                    handleDragLeave(e) { e.preventDefault(); this.isDragging = false; },
                                    handleDrop(e) {
                                        e.preventDefault();
                                        this.isDragging = false;
                                        const files = e.dataTransfer.files;
                                        if (files.length > 0) {
                                            const file = files[0];
                                            if (file.type === 'application/pdf') {
                                                this.isUploading = true;
                                                const input = this.$refs.fileInput;
                                                const dataTransfer = new DataTransfer();
                                                dataTransfer.items.add(file);
                                                input.files = dataTransfer.files;
                                                input.dispatchEvent(new Event('change', { bubbles: true }));
                                            } else {
                                                Swal.fire({ icon: 'error', title: 'Invalid File Type', text: 'Please upload a PDF file only.', confirmButtonColor: '#0A6025' });
                                            }
                                        }
                                    }
                                }"
                                x-init="$watch('$wire.requirements_file', value => { if (value) { isUploading = false; } })">
                                <label @dragover="handleDragOver" @dragleave="handleDragLeave" @drop="handleDrop"
                                    :class="{ 'border-[#0A6025] bg-green-50': isDragging }"
                                    class="flex items-center justify-center w-full px-4 py-6 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#0A6025] cursor-pointer transition-all duration-200">
                                    <div class="text-center">
                                        <svg x-show="!isUploading" class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                            <path stroke-linecap="round"
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" />
                                        </svg>
                                        <div x-show="isUploading" class="mx-auto">
                                            <svg class="animate-spin h-12 w-12 text-[#0A6025] mx-auto" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                                            </svg>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600" x-show="!isUploading"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="mt-2 text-sm font-semibold text-[#0A6025]" x-show="isUploading">Uploading file...</p>
                                        <p class="text-xs text-gray-500" x-show="!isUploading">PDF (MAX. 100MB)</p>
                                    </div>
                                    <input type="file" wire:model="requirements_file" accept="application/pdf" class="hidden" x-ref="fileInput" @change="isUploading = true">
                                </label>
                            </div>

                            @if ($requirements_file)
                            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg animate-fadeIn">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm font-medium text-green-700">{{ $requirements_file->getClientOriginalName() }}</span>
                                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">Ready to Replace</span>
                                    </div>
                                    <button type="button" wire:click="$set('requirements_file', null)" class="text-red-600 hover:text-red-800 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endif

                            <div wire:loading wire:target="requirements_file" class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-blue-700">Uploading file...</p>
                                        <div class="mt-1 w-full bg-blue-200 rounded-full h-1.5">
                                            <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 70%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('requirements_file')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <span class="text-red-600 text-sm flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </span>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- DATA PRIVACY AGREEMENT SECTION -->
                    <div class="pb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Data Privacy Agreement</h3>
                        </div>

                        <!-- Privacy Policy Box -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-5 max-h-64 overflow-y-auto text-sm text-gray-700 space-y-4 leading-relaxed">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span class="font-bold text-gray-900 text-base">Republic Act No. 10173 — Data Privacy Act of 2012</span>
                            </div>

                            <p>In compliance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong> and its Implementing Rules and Regulations, this institution is committed to protecting and respecting your privacy.</p>

                            <p><strong>Purpose of Data Collection</strong></p>
                            <p>The personal information you provide in this application form — including but not limited to your full name, contact details, address, educational background, work experience, and supporting documents — will be collected and processed solely for the purpose of evaluating your eligibility and qualifications for the position you are applying for.</p>

                            <p><strong>Data Use and Disclosure</strong></p>
                            <p>Your personal data will only be accessed by authorized personnel of this institution involved in the hiring and selection process. We will not share, sell, or disclose your personal information to third parties without your consent, except as required by law or authorized government agencies.</p>

                            <p><strong>Data Retention</strong></p>
                            <p>Your application data will be retained for a period necessary to fulfill the purposes stated above and in accordance with applicable laws and institutional policies. After such period, your data will be securely disposed of.</p>

                            <p><strong>Your Rights as a Data Subject</strong></p>
                            <p>Under the Data Privacy Act of 2012, you have the following rights:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Right to be informed of how your data is being processed</li>
                                <li>Right to access your personal data held by this institution</li>
                                <li>Right to correct any inaccurate or outdated information</li>
                                <li>Right to object to the processing of your personal data</li>
                                <li>Right to erasure or blocking of your personal data</li>
                                <li>Right to file a complaint with the National Privacy Commission</li>
                            </ul>

                            <p><strong>Security Measures</strong></p>
                            <p>This institution implements appropriate technical and organizational security measures to protect your personal data against unauthorized access, disclosure, alteration, or destruction. Uploaded documents are encrypted and stored securely.</p>

                            <p><strong>Contact Information</strong></p>
                            <p>If you have questions or concerns regarding the processing of your personal data, you may contact our Data Protection Officer through the institution's official communication channels.</p>

                            <p class="text-xs text-gray-500 pt-2 border-t border-gray-200">
                                By checking the box below, you acknowledge that you have read and understood this Data Privacy Notice and you freely give your consent to the collection and processing of your personal data for the purposes stated herein.
                            </p>
                        </div>

                        <!-- Checkbox Agreement -->
                        <div class="@error('agree_to_terms') bg-red-50 border border-red-200 @else bg-white border border-gray-200 @enderror rounded-xl p-4 transition-colors duration-200">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="relative flex-shrink-0 mt-0.5">
                                    <input type="checkbox" wire:model.live="agree_to_terms"
                                        class="w-5 h-5 rounded border-gray-300 text-[#0A6025] focus:ring-[#0A6025] cursor-pointer">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800 group-hover:text-[#0A6025] transition-colors">
                                        I have read and agree to the Data Privacy Act terms and conditions
                                        <span class="text-red-500">*</span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        I consent to the collection, use, and processing of my personal data as described in the Data Privacy Notice above, in accordance with Republic Act No. 10173.
                                    </p>
                                </div>
                            </label>
                        </div>

                        @error('agree_to_terms')
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                        <button type="button" onclick="history.back()"
                            class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            :class="$wire.agree_to_terms ? 'opacity-100 cursor-pointer' : 'opacity-50 cursor-not-allowed'"
                            style="background: linear-gradient(to right, #eab308, #15803d);"
                            class="px-6 py-3 hover:opacity-90 text-white font-semibold rounded-lg shadow-lg transition-all duration-300">
                            <span wire:loading.remove>Update Application</span>
                            <span wire:loading>⌛ Updating...</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- PDF VIEWER MODAL -->
    <div x-data="{
            open: false,
            loading: false,
            pdfUrl: null,
            async openPdfViewer() {
                this.loading = true;
                this.open = true;
                try {
                    const dataUrl = await @this.call('getFileDataUrl');
                    if (dataUrl) {
                        this.pdfUrl = dataUrl;
                    }
                } catch (error) {
                    console.error('Error loading PDF:', error);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load the PDF file.', confirmButtonColor: '#d33' });
                    this.open = false;
                } finally {
                    this.loading = false;
                }
            }
        }"
        x-on:open-pdf-viewer.window="openPdfViewer()"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-hidden"
        style="display: none;">

        <div class="absolute inset-0 bg-black bg-opacity-75" @click="open = false; pdfUrl = null;"></div>

        <div class="relative w-full h-full flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-6xl h-full max-h-screen flex flex-col">

                <div class="flex items-center justify-between px-6 py-4 border-b bg-white rounded-t-lg">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                        </svg>
                        Application Requirements
                    </h3>
                    <button @click="open = false; pdfUrl = null;" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-hidden rounded-b-lg">
                    <div x-show="loading" class="flex flex-col items-center justify-center h-full gap-4">
                        <svg class="animate-spin h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">Decrypting and loading file...</p>
                    </div>

                    <iframe x-show="!loading && pdfUrl" :src="pdfUrl" class="w-full h-full" frameborder="0"></iframe>

                    <div x-show="!loading && !pdfUrl" class="flex flex-col items-center justify-center h-full gap-4">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 text-sm">Unable to load the file.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SWEETALERT2 INTEGRATION -->
    <div x-data
        x-on:show-swal-confirm.window="
            Swal.fire({
                title: 'Update Job Application?',
                text: 'Please confirm that all details are correct before updating.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0A6025',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Update',
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
        "
        x-on:show-error.window="
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: $event.detail.message,
                confirmButtonColor: '#d33'
            });
        ">
    </div>

    <style>
        [x-cloak] { display: none !important; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
    </style>
</div>