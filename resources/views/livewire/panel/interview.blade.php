<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold text-[#0A6025] mb-2">
                            Interview Evaluation
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Evaluate the applicant's interview performance
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                <div class="bg-[#0A6025] p-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">I. Interview</h2>
                    </div>
                </div>

                <div class="p-8">

                    <!-- Instructions -->
                    <div class="mb-8 bg-gradient-to-r from-[#0A6025]/10 to-green-50 border-l-4 border-[#0A6025] p-6 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-[#0A6025] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-bold text-lg mb-2 text-[#0A6025]">To the interviewer:</h3>
                                <p class="text-gray-700">
                                    A particular subject matter is given to the applicant. He may be opt to find to select a subject matter 
                                    which is within his/her area of specialization and is allowed to prepare within period of five minutes.
                                </p>
                            </div>
                        </div>
                    </div>

            <form wire:submit.prevent="confirmSubmission">
                @if ($currentPage == 1)
                    <!-- Page 1: General Appearance through Alertness -->
                    <div class="space-y-8">
                        <!-- I. General Appearance -->
                        <div class="border-b pb-6">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-lg">I. General Appearance:</h4>
                                <div class="flex gap-8 text-center">
                                    <span class="w-12 font-semibold">5</span>
                                    <span class="w-12 font-semibold">4</span>
                                    <span class="w-12 font-semibold">3</span>
                                    <span class="w-12 font-semibold">2</span>
                                    <span class="w-12 font-semibold">1</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-700 flex-1">
                                    Consider the total effect of the applicant's appearance. How does his/her appearance impress you?
                                </p>
                                <div class="flex gap-8">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" 
                                                   wire:model="general_appearance" 
                                                   value="{{ $i }}"
                                                   class="w-6 h-6 cursor-pointer">
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('general_appearance') 
                                <span class="text-red-500 text-sm mt-2">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- II. Manner Speaking -->
                        <div class="border-b pb-6">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-lg">II. Manner Speaking:</h4>
                                <div class="flex gap-8 text-center">
                                    <span class="w-12 font-semibold">5</span>
                                    <span class="w-12 font-semibold">4</span>
                                    <span class="w-12 font-semibold">3</span>
                                    <span class="w-12 font-semibold">2</span>
                                    <span class="w-12 font-semibold">1</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-700 flex-1">
                                    How well does he/she talk? Does he express himself clearly and adequately
                                </p>
                                <div class="flex gap-8">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" 
                                                   wire:model="manner_of_speaking" 
                                                   value="{{ $i }}"
                                                   class="w-6 h-6 cursor-pointer">
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('manner_of_speaking') 
                                <span class="text-red-500 text-sm mt-2">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- III. Physical Conditioning -->
                        <div class="border-b pb-6">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-lg">III. Physical Conditioning:</h4>
                                <div class="flex gap-8 text-center">
                                    <span class="w-12 font-semibold">5</span>
                                    <span class="w-12 font-semibold">4</span>
                                    <span class="w-12 font-semibold">3</span>
                                    <span class="w-12 font-semibold">2</span>
                                    <span class="w-12 font-semibold">1</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-700 flex-1">
                                    How physically energetic he/she is?
                                </p>
                                <div class="flex gap-8">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" 
                                                   wire:model="physical_conditions" 
                                                   value="{{ $i }}"
                                                   class="w-6 h-6 cursor-pointer">
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('physical_conditions') 
                                <span class="text-red-500 text-sm mt-2">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- IV. Alertness -->
                        <div class="pb-6">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-lg">IV. Alertness:</h4>
                                <div class="flex gap-8 text-center">
                                    <span class="w-12 font-semibold">5</span>
                                    <span class="w-12 font-semibold">4</span>
                                    <span class="w-12 font-semibold">3</span>
                                    <span class="w-12 font-semibold">2</span>
                                    <span class="w-12 font-semibold">1</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-start">
                                <p class="text-gray-700 flex-1 pr-4">
                                    Consider the applicant's ability to comprehend your questions speedily and anticipate your thought. 
                                    Has the capacity to transfer attention from one subject to another quickly? Is there a lag in his/her 
                                    reaction to your discussion? How mentally alert is he/she?
                                </p>
                                <div class="flex gap-8 flex-shrink-0">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" 
                                                   wire:model="alertness" 
                                                   value="{{ $i }}"
                                                   class="w-6 h-6 cursor-pointer">
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('alertness') 
                                <span class="text-red-500 text-sm mt-2">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Navigation Buttons Page 1 -->
                    <div class="flex justify-center gap-4 mt-8">
                        @if (session()->has('error'))
                            <div class="w-full text-center mb-4">
                                <span class="text-red-500 font-semibold">{{ session('error') }}</span>
                            </div>
                        @endif
                        <button type="button" 
                                wire:click="nextPage"
                                class="bg-[#0A6025] hover:bg-[#0B712C] text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                            Next →
                        </button>
                    </div>

                @elseif ($currentPage == 2)
                    <!-- Page 2: Self Confidence through Maturity of Judgement -->
                    <div class="space-y-8">
                        <!-- V. Self Confidence -->
                        <div class="border-b pb-6">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-lg">V. Self Confidence:</h4>
                                <div class="flex gap-8 text-center">
                                    <span class="w-12 font-semibold">5</span>
                                    <span class="w-12 font-semibold">4</span>
                                    <span class="w-12 font-semibold">3</span>
                                    <span class="w-12 font-semibold">2</span>
                                    <span class="w-12 font-semibold">1</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-700 flex-1">
                                    How self assuring he/she? Is he/she wholesomely self confident and assured if does he/she see, uncertain of himself?
                                </p>
                                <div class="flex gap-8">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" 
                                                   wire:model="self_confidence" 
                                                   value="{{ $i }}"
                                                   class="w-6 h-6 cursor-pointer">
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('self_confidence') 
                                <span class="text-red-500 text-sm mt-2">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- VI. Ability to Present Ideas -->
                        <div class="border-b pb-6">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-lg">VI. Ability to Present Ideas:</h4>
                                <div class="flex gap-8 text-center">
                                    <span class="w-12 font-semibold">5</span>
                                    <span class="w-12 font-semibold">4</span>
                                    <span class="w-12 font-semibold">3</span>
                                    <span class="w-12 font-semibold">2</span>
                                    <span class="w-12 font-semibold">1</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-700 flex-1">
                                    Does he/she present relevant, clear, and logical ideas?
                                </p>
                                <div class="flex gap-8">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" 
                                                   wire:model="ability_to_present_ideas" 
                                                   value="{{ $i }}"
                                                   class="w-6 h-6 cursor-pointer">
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('ability_to_present_ideas') 
                                <span class="text-red-500 text-sm mt-2">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- VII. Maturity of Judgement -->
                        <div class="pb-6">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-lg">VII. Maturity of Judgement:</h4>
                                <div class="flex gap-8 text-center">
                                    <span class="w-12 font-semibold">5</span>
                                    <span class="w-12 font-semibold">4</span>
                                    <span class="w-12 font-semibold">3</span>
                                    <span class="w-12 font-semibold">2</span>
                                    <span class="w-12 font-semibold">1</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-gray-700 flex-1">
                                    Could he/she judiculously act on a given situation? Does his/her judegement reflectanalytical vision?
                                </p>
                                <div class="flex gap-8">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <label class="flex items-center justify-center w-12">
                                            <input type="radio" 
                                                   wire:model="maturity_of_judgement" 
                                                   value="{{ $i }}"
                                                   class="w-6 h-6 cursor-pointer">
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('maturity_of_judgement') 
                                <span class="text-red-500 text-sm mt-2">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Navigation Buttons Page 2 -->
                    <div class="mt-8">
                        @if (session()->has('error'))
                            <div class="w-full text-center mb-4">
                                <span class="text-red-500 font-semibold">{{ session('error') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-center gap-4">
                            <button type="button" 
                                    wire:click="previousPage"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                ← Return
                            </button>
                            @php
                                $applicantPosition = $evaluation->jobApplication->position->name ?? null;
                            @endphp
                            <button type="submit"
                                    class="bg-[#0A6025] hover:bg-[#0B712C] text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                @if($applicantPosition === 'Instructor I')
                                    Next →
                                @else
                                    Submit ✓
                                @endif
                            </button>
                        </div>
                    </div>
                @endif
                </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Integration - FIXED to match Performance page -->
    <div x-data="{ 
        init() {
            window.addEventListener('show-swal-confirm', () => {
                Swal.fire({
                    title: 'Submit Interview Evaluation?',
                    text: 'Please confirm that all ratings are correct before submitting.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0A6025',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Submit'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('saveInterview');
                    }
                });
            });

            window.addEventListener('interview-saved', () => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Interview evaluation saved successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0A6025'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('panel.dashboard') }}';
                    }
                });
            });
        }
    }">
    </div>
</div>