<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold text-[#0A6025] mb-2">
                            Performance Evaluation
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Evaluate applicant's mock lecture/demonstration performance
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
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">III. MOCK LECTURE/DEMONSTRATION</h2>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Preliminaries Section -->
                    <div class="mb-6 bg-gradient-to-r from-[#0A6025]/10 to-green-50 border-l-4 border-[#0A6025] p-6 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-[#0A6025] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-bold text-lg mb-3 text-[#0A6025]">Preliminaries</h3>
                                <p class="text-gray-700 mb-4">
                                    A particular subject matter is given to the applicant. He may be opt to find to select a subject matter 
                                    which is within his/her area of specialization and is allowed to prepare within period of five minutes.
                                </p>
                                <h4 class="font-bold text-base mb-2 text-[#0A6025]">Actual Lecturer/ Demonstration</h4>
                                <p class="text-gray-700">
                                    A mock situation in a classroom/outside is provided, and the candidate demonstrates. He is given 20 minutes 
                                    to do the task. The following guide may serve as indicates in grading the applicant
                                </p>
                            </div>
                        </div>
                    </div>

            <form wire:submit.prevent="savePerformance">
                @if ($currentPage == 1)
                    <!-- Page 1: Instructional Competence/Skill -->
                    <div class="space-y-6">
                        <!-- Section Header -->
                        <div class="flex justify-between items-center mb-6 border-b pb-4">
                            <h4 class="font-bold text-lg">1. Instructional Competence/Skill</h4>
                            <div class="flex gap-12 text-center">
                                <span class="w-16 font-semibold">VS</span>
                                <span class="w-16 font-semibold">S</span>
                                <span class="w-16 font-semibold">F</span>
                                <span class="w-16 font-semibold">P</span>
                                <span class="w-16 font-semibold">NI</span>
                            </div>
                        </div>

                        <!-- Question 1.1 -->
                        <div class="flex justify-between items-center py-4 border-b">
                            <p class="text-gray-700 flex-1 pr-8">
                                1.1 His/her knowledgeability and mastery of the subject matter
                            </p>
                            <div class="flex gap-12">
                                @foreach(['VS', 'S', 'F', 'P', 'NI'] as $value)
                                    <label class="flex items-center justify-center w-16">
                                        <input type="radio" 
                                               wire:model="question1" 
                                               value="{{ $value }}"
                                               class="w-6 h-6 cursor-pointer">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('question1') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror

                        <!-- Question 1.2 -->
                        <div class="flex justify-between items-center py-4 border-b">
                            <p class="text-gray-700 flex-1 pr-8">
                                1.2 His/her way of communication has ideas to students
                            </p>
                            <div class="flex gap-12">
                                @foreach(['VS', 'S', 'F', 'P', 'NI'] as $value)
                                    <label class="flex items-center justify-center w-16">
                                        <input type="radio" 
                                               wire:model="question2" 
                                               value="{{ $value }}"
                                               class="w-6 h-6 cursor-pointer">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('question2') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror

                        <!-- Question 1.3 -->
                        <div class="flex justify-between items-center py-4 border-b">
                            <p class="text-gray-700 flex-1 pr-8">
                                1.3 His/her skill in initiating the lesson
                            </p>
                            <div class="flex gap-12">
                                @foreach(['VS', 'S', 'F', 'P', 'NI'] as $value)
                                    <label class="flex items-center justify-center w-16">
                                        <input type="radio" 
                                               wire:model="question3" 
                                               value="{{ $value }}"
                                               class="w-6 h-6 cursor-pointer">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('question3') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror

                        <!-- Question 1.4 -->
                        <div class="flex justify-between items-center py-4 border-b">
                            <p class="text-gray-700 flex-1 pr-8">
                                1.4 His/her way of motivating students to participate actively in classroom task
                            </p>
                            <div class="flex gap-12">
                                @foreach(['VS', 'S', 'F', 'P', 'NI'] as $value)
                                    <label class="flex items-center justify-center w-16">
                                        <input type="radio" 
                                               wire:model="question4" 
                                               value="{{ $value }}"
                                               class="w-6 h-6 cursor-pointer">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('question4') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror

                        <!-- Question 1.5 -->
                        <div class="flex justify-between items-center py-4">
                            <p class="text-gray-700 flex-1 pr-8">
                                1.5 His/her skill in sustaining the interest of the learners
                            </p>
                            <div class="flex gap-12">
                                @foreach(['VS', 'S', 'F', 'P', 'NI'] as $value)
                                    <label class="flex items-center justify-center w-16">
                                        <input type="radio" 
                                               wire:model="question5" 
                                               value="{{ $value }}"
                                               class="w-6 h-6 cursor-pointer">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('question5') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Navigation Buttons Page 1 -->
                    <div class="mt-8">
                        @if (session()->has('error'))
                            <div class="w-full text-center mb-4">
                                <span class="text-red-500 font-semibold">{{ session('error') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('panel.interview', $evaluationId) }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                ← Return
                            </a>
                            <button type="button" 
                                    wire:click="nextPage"
                                    class="bg-[#0A6025] hover:bg-[#0B712C] text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                Next →
                            </button>
                        </div>
                    </div>

                @elseif ($currentPage == 2)
                    <!-- Page 2: Personal Competence -->
                    <div class="space-y-6">
                        <!-- Section Header -->
                        <div class="flex justify-between items-center mb-6 border-b pb-4">
                            <h4 class="font-bold text-lg">2. Personal Competence</h4>
                            <div class="flex gap-12 text-center">
                                <span class="w-16 font-semibold">VS</span>
                                <span class="w-16 font-semibold">S</span>
                                <span class="w-16 font-semibold">F</span>
                                <span class="w-16 font-semibold">P</span>
                                <span class="w-16 font-semibold">NI</span>
                            </div>
                        </div>

                        <!-- Question 2.1 -->
                        <div class="flex justify-between items-center py-4">
                            <p class="text-gray-700 flex-1 pr-8">
                                2.1 The manifestation of composed behavior while teaching
                            </p>
                            <div class="flex gap-12">
                                @foreach(['VS', 'S', 'F', 'P', 'NI'] as $value)
                                    <label class="flex items-center justify-center w-16">
                                        <input type="radio" 
                                               wire:model="personalQuestion1" 
                                               value="{{ $value }}"
                                               class="w-6 h-6 cursor-pointer">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('personalQuestion1') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
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
                            <button type="submit"
                                    class="bg-[#0A6025] hover:bg-[#0B712C] text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                Submit ✓
                            </button>
                        </div>
                    </div>
                @endif
                </form>
                </div>
            </div>
        </div>
    </div>
</div>