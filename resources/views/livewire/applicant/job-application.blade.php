<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-yellow-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-4xl mx-auto">

            <!-- COUNTDOWN TIMER -->
            <div class="mb-6 p-4 bg-white shadow-md rounded-xl border-l-4 border-[#0A6025]" x-data="{
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
                        return `${String(days).padStart(2,'0')}d : ${String(hours).padStart(2,'0')}h : ${String(minutes).padStart(2,'0')}m : ${String(seconds).padStart(2,'0')}s`;
                    },
                    init() {
                        this.remaining = this.deadline - this.now;
                        this.timer = setInterval(() => {
                            this.now = Date.now();
                            this.remaining = this.deadline - this.now;
                        }, 1000);
                    }
                }">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Application Deadline Countdown
                </h2>
                <p class="mt-1 text-2xl font-extrabold text-[#0A6025]"
                    x-text="remaining > 0 ? format(remaining) : 'Deadline Passed'"></p>
            </div>

            <!-- STEP PROGRESS BAR -->
            <div class="mb-8 bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-extrabold text-gray-800">Job Application Form</h1>
                    <span class="text-sm font-medium text-gray-500">Step {{ $currentStep }} of {{ $totalSteps }}</span>
                </div>

                <!-- Steps -->
                <div class="flex items-center gap-0">
                    @php
                    $steps = [
                    1 => ['label' => 'Personal Info', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7
                    7 0 00-7-7z'],
                    2 => ['label' => 'Address', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827
                    0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    3 => ['label' => 'Employment', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183
                    0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0
                    00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    4 => ['label' => 'Documents', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0
                    00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    ];
                    @endphp

                    @foreach($steps as $stepNum => $stepData)
                    <div class="flex items-center {{ $stepNum < $totalSteps ? 'flex-1' : '' }}">
                        <!-- Step circle -->
                        @if($stepNum < $currentStep) <button type="button" wire:click="goToStep({{ $stepNum }})"
                            class="flex flex-col items-center group cursor-pointer">
                            @else
                            <div class="flex flex-col items-center group cursor-default">
                                @endif

                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-300
                {{ $stepNum < $currentStep ? 'bg-[#0A6025] border-[#0A6025]' : ($stepNum === $currentStep ? 'bg-white border-[#0A6025]' : 'bg-white border-gray-300') }}">
                                    @if($stepNum < $currentStep) <svg class="w-5 h-5 text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4 {{ $stepNum === $currentStep ? 'text-[#0A6025]' : 'text-gray-400' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $stepData['icon'] }}" />
                                        </svg>
                                        @endif
                                </div>
                                <span
                                    class="mt-1 text-xs font-medium {{ $stepNum === $currentStep ? 'text-[#0A6025]' : ($stepNum < $currentStep ? 'text-gray-600' : 'text-gray-400') }} hidden sm:block">
                                    {{ $stepData['label'] }}
                                </span>

                                @if($stepNum < $currentStep) </button>
                                    @else
                            </div>
                            @endif

                            <!-- Connector line -->
                            @if($stepNum < $totalSteps) <div class="flex-1 h-0.5 mx-2 mt-[-16px] sm:mt-[-10px] transition-all duration-500
                {{ $stepNum < $currentStep ? 'bg-[#0A6025]' : 'bg-gray-200' }}">
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- FORM CARD -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
            <form wire:submit.prevent="confirmSubmission" x-data
                x-on:scroll-to-error.window="setTimeout(() => { const el = document.querySelector('.input-error'); if(el) el.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 100);"
                x-on:step-changed.window="window.scrollTo({ top: 0, behavior: 'smooth' });" class="p-8 space-y-6">
                @csrf

                {{-- ═══════════════ STEP 1: PERSONAL INFORMATION ═══════════════ --}}
                @if($currentStep === 1)
                <div class="animate-fadeIn">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-gradient-to-br from-yellow-500 to-[#0A6025] rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Personal Information</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Please provide your basic personal details</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">First Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="first_name"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('first_name') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-transparent transition">
                            @error('first_name')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Middle Name <span
                                    class="text-gray-400 font-normal">(Optional)</span></label>
                            <input type="text" wire:model="middle_name"
                                class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="last_name"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('last_name') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-transparent transition">
                            @error('last_name')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Suffix <span
                                    class="text-gray-400 font-normal">(Optional)</span></label>
                            <select wire:model="suffix"
                                class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-transparent transition">
                                <option value="">Select Suffix</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Number <span
                                    class="text-red-500">*</span></label>
                            <div class="relative max-w-xs">
                                <input type="text" wire:model.live="phone_number" placeholder="09XXXXXXXXX"
                                    maxlength="11" pattern="[0-9]*" inputmode="numeric"
                                    class="block w-full px-4 py-3 bg-gray-50 border @error('phone_number') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-transparent transition"
                                    x-data
                                    x-on:keypress="if (!/[0-9]/.test(String.fromCharCode($event.keyCode))) $event.preventDefault()"
                                    x-on:paste.prevent="
                                            let paste = ($event.clipboardData || window.clipboardData).getData('text');
                                            paste = paste.replace(/[^0-9]/g, '');
                                            $event.target.value = paste.substring(0, 11);
                                            $event.target.dispatchEvent(new Event('input'));
                                        ">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Format: 09XXXXXXXXX (11 digits starting with 09)</p>
                            @error('phone_number')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>
                    </div>
                </div>
                @endif

                {{-- ═══════════════ STEP 2: ADDRESS INFORMATION ═══════════════ --}}
                @if($currentStep === 2)
                <div class="animate-fadeIn">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Address Information</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Provide your current residential address</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Region <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="region"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('region') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition">
                                <option value="">Select Region</option>
                                @foreach($regions as $reg)
                                <option value="{{ $reg['name'] }}">{{ $reg['regionName'] ?? $reg['name'] }}</option>
                                @endforeach
                            </select>
                            @error('region')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Province <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="province"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('province') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition {{ !$region ? 'opacity-60 cursor-not-allowed' : '' }}"
                                @if(!$region) disabled @endif>
                                <option value="">{{ $region ? 'Select Province' : 'Select Region first' }}</option>
                                @foreach($provinces as $prov)
                                <option value="{{ $prov['name'] }}">{{ $prov['name'] }}</option>
                                @endforeach
                            </select>
                            @error('province')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">City/Municipality <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="city"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('city') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition {{ !$province ? 'opacity-60 cursor-not-allowed' : '' }}"
                                @if(!$province) disabled @endif>
                                <option value="">{{ $province ? 'Select City/Municipality' : 'Select Province first' }}
                                </option>
                                @foreach($cities as $ct)
                                <option value="{{ $ct['name'] }}">{{ $ct['name'] }}</option>
                                @endforeach
                            </select>
                            @error('city')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Barangay <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="barangay"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('barangay') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition {{ !$city ? 'opacity-60 cursor-not-allowed' : '' }}"
                                @if(!$city) disabled @endif>
                                <option value="">{{ $city ? 'Select Barangay' : 'Select City first' }}</option>
                                @foreach($barangays as $brgy)
                                <option value="{{ $brgy['name'] }}">{{ $brgy['name'] }}</option>
                                @endforeach
                            </select>
                            @error('barangay')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Street/Building <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="street" placeholder="House No., Street Name, Building"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('street') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition">
                            @error('street')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Postal Code <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="postal_code" placeholder="e.g., 1234"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('postal_code') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition">
                            @error('postal_code')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>
                    </div>
                </div>
                @endif

                {{-- ═══════════════ STEP 3: EMPLOYMENT INFORMATION ═══════════════ --}}
                @if($currentStep === 3)
                <div class="animate-fadeIn">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Employment Information</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Share your professional background and
                                qualifications</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Present Position <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="present_position"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('present_position') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition">
                            @error('present_position')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Years of Experience <span
                                    class="text-red-500">*</span></label>
                            <input type="number" wire:model="experience" min="0"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('experience') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition">
                            @error('experience')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Education <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="education" list="educationOptions"
                                placeholder="e.g., Master of Science in Information Technology"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('education') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition">
                            <datalist id="educationOptions">
                                @foreach($educationOptions as $option)
                                <option value="{{ $option }}"></option>
                                @endforeach
                            </datalist>
                            @error('education')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Training (Hours) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" wire:model="training" min="0"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('training') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition">
                            @error('training')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Eligibility <span
                                    class="text-red-500">*</span></label>
                            @if($eligibilityIsFixed)
                            <div class="relative">
                                <input type="text" value="None Required" disabled
                                    class="block w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-500 cursor-not-allowed">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="hidden" wire:model="eligibility">
                            <p class="mt-1 text-xs text-gray-400 italic flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                Automatically set to "None Required" for this position.
                            </p>
                            @else
                            <input type="text" wire:model="eligibility"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('eligibility') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition">
                            @endif
                            @error('eligibility')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Other Involvement <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="other_involvement"
                                class="block w-full px-4 py-3 bg-gray-50 border @error('other_involvement') input-error border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#0A6025] transition">
                            @error('other_involvement')<span class="text-red-500 text-sm mt-1 block">{{ $message
                                }}</span>@enderror
                        </div>
                    </div>
                </div>
                @endif

                {{-- ═══════════════ STEP 4: DOCUMENTS & PRIVACY ═══════════════ --}}
                @if($currentStep === 4)
                <div class="animate-fadeIn space-y-8">

                    <!-- Required Documents -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Required Documents</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Upload your compiled PDF requirements</p>
                            </div>
                        </div>

                        <!-- Document Instructions -->
                        <div class="mb-5 bg-amber-50 border border-amber-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 text-sm text-amber-800">
                                    <p class="font-bold text-amber-900 mb-2">📄 Please merge all your documents into one
                                        PDF File:</p>
                                    <ol class="list-decimal list-inside space-y-1 text-amber-800">
                                        <li>Application letter addressed to <strong>Dr. Evaristo A. Abella, University
                                                President</strong></li>
                                        <li>Detailed Resume</li>
                                        <li>Copies of diploma, TOR, PRC license or certificate of registration</li>
                                        <li>Certificate of Employment / Service Record, Training Certificates</li>
                                        <li>Other credentials (Journals, Publications, Speakership in Conferences, etc.)
                                        </li>
                                        <li>Applicants for <strong>Instructor I to Assistant Professor I</strong>
                                            positions shall only submit an E-copy of their credentials</li>
                                    </ol>

                                    <div class="mt-3 pt-3 border-t border-amber-300">
                                        <p class="font-bold text-amber-900 mb-1">📌 ADDITIONAL REQUIREMENTS:</p>
                                        <ol class="list-decimal list-inside space-y-1 text-amber-800" start="7">
                                            <li>For <strong>Instructor III, Assistant Professor III to Associate
                                                    Professor IV</strong> Positions — One (1) Original and one (1)
                                                photocopy of credentials for NBC evaluation <em>(hard copies)</em></li>
                                            <li>
                                                For <strong>Professorial Positions</strong> — One (1) Original and four
                                                (4) photocopy of credentials for NBC en banc evaluation <em>(hard
                                                    copies)</em>
                                                <ul class="list-none pl-4 mt-0.5">
                                                    <li class="text-amber-700 italic">— If NBC documents have been
                                                        previously evaluated, submit only the 5 copies of your
                                                        additional credentials.</li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Area -->
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
                                :class="{ 'border-[#0A6025] bg-green-50 scale-[1.01]': isDragging }"
                                class="flex items-center justify-center w-full px-4 py-10 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl hover:border-[#0A6025] cursor-pointer transition-all duration-200">
                                <div class="text-center">
                                    <svg x-show="!isUploading" class="mx-auto h-14 w-14 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round"
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" />
                                    </svg>
                                    <div x-show="isUploading" class="mx-auto">
                                        <svg class="animate-spin h-14 w-14 text-[#0A6025] mx-auto" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                                        </svg>
                                    </div>
                                    <p class="mt-3 text-sm text-gray-600" x-show="!isUploading">
                                        <span class="font-semibold text-[#0A6025]">Click to upload</span> or drag and
                                        drop
                                    </p>
                                    <p class="mt-2 text-sm font-semibold text-[#0A6025]" x-show="isUploading">Uploading
                                        file...</p>
                                    <p class="text-xs text-gray-500 mt-1" x-show="!isUploading">PDF only — MAX. 100MB
                                    </p>
                                </div>
                                <input type="file" wire:model="requirements_file" accept="application/pdf"
                                    class="hidden" x-ref="fileInput" @change="isUploading = true">
                            </label>
                        </div>

                        @if ($requirements_file)
                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg animate-fadeIn">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium text-green-700">{{
                                        $requirements_file->getClientOriginalName() }}</span>
                                    <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">Ready to
                                        Upload</span>
                                </div>
                                <button type="button" wire:click="$set('requirements_file', null)"
                                    class="text-red-600 hover:text-red-800 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endif

                        <div wire:loading wire:target="requirements_file"
                            class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                                </svg>
                                <p class="text-sm font-medium text-blue-700">Uploading file...</p>
                            </div>
                        </div>

                        @error('requirements_file')
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <span class="text-red-600 text-sm flex items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </span>
                        </div>
                        @enderror
                    </div>

                    <!-- Data Privacy -->
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg p-2">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Data Privacy Agreement</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Please read and accept the terms below</p>
                            </div>
                        </div>

                        <div
                            class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-5 max-h-64 overflow-y-auto text-sm text-gray-700 space-y-4 leading-relaxed">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span class="font-bold text-gray-900 text-base">Republic Act No. 10173 — Data Privacy
                                    Act of 2012</span>
                            </div>
                            <p>In compliance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>
                                and its Implementing Rules and Regulations, this institution is committed to protecting
                                and respecting your privacy.</p>
                            <p><strong>Purpose of Data Collection</strong></p>
                            <p>The personal information you provide in this application form — including but not limited
                                to your full name, contact details, address, educational background, work experience,
                                and supporting documents — will be collected and processed solely for the purpose of
                                evaluating your eligibility and qualifications for the position you are applying for.
                            </p>
                            <p><strong>Data Use and Disclosure</strong></p>
                            <p>Your personal data will only be accessed by authorized personnel of this institution
                                involved in the hiring and selection process. We will not share, sell, or disclose your
                                personal information to third parties without your consent, except as required by law or
                                authorized government agencies.</p>
                            <p><strong>Data Retention</strong></p>
                            <p>Your application data will be retained for a period necessary to fulfill the purposes
                                stated above and in accordance with applicable laws and institutional policies. After
                                such period, your data will be securely disposed of.</p>
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
                            <p>This institution implements appropriate technical and organizational security measures to
                                protect your personal data against unauthorized access, disclosure, alteration, or
                                destruction. Uploaded documents are encrypted and stored securely.</p>
                            <p><strong>Contact Information</strong></p>
                            <p>If you have questions or concerns regarding the processing of your personal data, you may
                                contact our Data Protection Officer through the institution's official communication
                                channels.</p>
                            <p class="text-xs text-gray-500 pt-2 border-t border-gray-200">
                                By checking the box below, you acknowledge that you have read and understood this Data
                                Privacy Notice and you freely give your consent to the collection and processing of your
                                personal data for the purposes stated herein.
                            </p>
                        </div>

                        <div
                            class="@error('agree_to_terms') bg-red-50 border border-red-200 @else bg-white border border-gray-200 @enderror rounded-xl p-4 transition-colors duration-200">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="relative flex-shrink-0 mt-0.5">
                                    <input type="checkbox" wire:model.live="agree_to_terms"
                                        class="w-5 h-5 rounded border-gray-300 text-[#0A6025] focus:ring-[#0A6025] cursor-pointer">
                                </div>
                                <div class="flex-1">
                                    <p
                                        class="text-sm font-semibold text-gray-800 group-hover:text-[#0A6025] transition-colors">
                                        I have read and agree to the Data Privacy Act terms and conditions
                                        <span class="text-red-500">*</span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        I consent to the collection, use, and processing of my personal data as
                                        described in the Data Privacy Notice above, in accordance with Republic Act No.
                                        10173.
                                    </p>
                                </div>
                            </label>
                        </div>

                        @error('agree_to_terms')
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        </div>
                        @enderror
                    </div>
                </div>
                @endif

                {{-- ═══════════════ NAVIGATION BUTTONS ═══════════════ --}}
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <!-- Left: Back / Cancel -->
                    <div>
                        @if($currentStep > 1)
                        <button type="button" wire:click="previousStep"
                            class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Back
                        </button>
                        @else
                        <button type="button" onclick="history.back()"
                            class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </button>
                        @endif
                    </div>

                    <!-- Right: Next / Submit -->
                    <div>
                        @if($currentStep < $totalSteps) <button type="button" wire:click="nextStep"
                            wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                            wire:target="nextStep"
                            class="inline-flex items-center gap-2 px-6 py-3 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 hover:opacity-90"
                            style="background: linear-gradient(to right, #eab308, #15803d);">
                            <span wire:loading.remove wire:target="nextStep">
                                Next
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                            <span wire:loading wire:target="nextStep">
                                <svg class="animate-spin w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                                </svg>
                                Validating...
                            </span>
                            </button>
                            @else
                            <button type="submit" wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                                :class="$wire.agree_to_terms ? 'opacity-100 cursor-pointer' : 'opacity-50 cursor-not-allowed'"
                                style="background: linear-gradient(to right, #eab308, #15803d);"
                                class="px-6 py-3 hover:opacity-90 text-white font-semibold rounded-lg shadow-lg transition-all duration-300">
                                <span wire:loading.remove>Submit Application</span>
                                <span wire:loading>⌛ Submitting...</span>
                            </button>
                            @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- SWEETALERT2 INTEGRATION -->
<div x-data x-on:show-swal-confirm.window="
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
        " x-on:swal:success.window="
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

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.35s ease-out;
    }
</style>
</div>