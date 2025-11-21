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
            <h2 class="text-3xl font-bold text-center mb-8">III. MOCK LECTURE/DEMONSTRATION</h2>

            <!-- Preliminaries Section -->
            <div class="mb-6 bg-gray-50 p-6 rounded-lg">
                <h3 class="font-bold text-lg mb-3">Preliminaries</h3>
                <p class="text-gray-700 mb-4">
                    A particular subject matter is given to the applicant. He may be opt to find to select a subject matter 
                    which is within his/her area of specialization and is allowed to prepare within period of five minutes.
                </p>
                <h4 class="font-bold text-base mb-2">Actual Lecturer/ Demonstration</h4>
                <p class="text-gray-700">
                    A mock situation in a classroom/outside is provided, and the candidate demonstrates. He is given 20 minutes 
                    to do the task. The following guide may serve as indicates in grading the applicant
                </p>
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
                               class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-150">
                                Return
                            </a>
                            <button type="button" 
                                    wire:click="nextPage"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-150">
                                Next
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
                                    class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-150">
                                Return
                            </button>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-150">
                                Submit
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>