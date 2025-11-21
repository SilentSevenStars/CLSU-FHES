<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="bg-white shadow-sm rounded-lg mb-6 px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Applicant Details</h1>
            {{-- <a href="{{ route('panel.applicant.view', $evaluation->job_application_id) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-150">
                View here
            </a> --}}
        </div>

        <!-- Main Content -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-8">I. Interview</h2>

            <!-- Instructions -->
            <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                <h3 class="font-bold text-lg mb-2">To the interviewer:</h3>
                <p class="text-gray-700">
                    A particular subject matter is given to the applicant. He may be opt to find to select a subject matter 
                    which is within his/her area of specialization and is allowed to prepare within period of five minutes.
                </p>
            </div>

            <form wire:submit.prevent="saveInterview">
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
                                class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-150">
                            Next
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
                                    class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-150">
                                Return
                            </button>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-150">
                                Next
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>