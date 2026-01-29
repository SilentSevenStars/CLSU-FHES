<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-6 animate-fadeIn">
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

            <!-- Applicant Details Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 hover:shadow-lg transition-shadow duration-200 cursor-pointer border-l-4 border-blue-600" 
                 wire:click="toggleApplicantModal">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Applicant Details</h3>
                            <p class="text-sm text-gray-600">{{ $applicant->full_name }} - {{ $position->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-blue-600 font-semibold">
                        <span>View Here</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
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

            <form wire:submit.prevent="confirmSubmission">
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
                            <button type="button"
                                    wire:click="returnToInterview"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                ← Return
                            </button>
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

    <!-- Applicant Details Modal -->
    @if($showApplicantModal)
        <div 
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            x-data="{ show: @entangle('showApplicantModal') }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
                <div class="flex items-center justify-between border-b pb-3 mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Applicant Details</h3>
                    <button 
                        wire:click="toggleApplicantModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Full Name</p>
                            <p class="mt-1 text-base text-gray-900">{{ $applicant->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="mt-1 text-base text-gray-900">{{ $applicant->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Phone Number</p>
                            <p class="mt-1 text-base text-gray-900">{{ $applicant->phone_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Position Applied</p>
                            <p class="mt-1 text-base text-gray-900">{{ $position->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Department</p>
                            <p class="mt-1 text-base text-gray-900">{{ $position->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">College</p>
                            <p class="mt-1 text-base text-gray-900">{{ $position->college->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($applicant->region || $applicant->city)
                    <div class="pt-4 border-t">
                        <p class="text-sm font-medium text-gray-500 mb-2">Address</p>
                        <p class="text-base text-gray-900">
                            {{ collect([$applicant->street, $applicant->barangay, $applicant->city, $applicant->province, $applicant->region])->filter()->join(', ') }}
                        </p>
                    </div>
                    @endif

                    @if($jobApplication->requirements_file)
                    <div class="pt-4 border-t">
                        <p class="text-sm font-medium text-gray-500 mb-2">Requirements File</p>
                        <a href="{{ Storage::url($jobApplication->requirements_file) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            View Requirements File
                        </a>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button 
                        wire:click="toggleApplicantModal"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- SweetAlert2 Integration -->
    <div x-data="{ 
        init() {
            window.addEventListener('show-swal-confirm', () => {
                Swal.fire({
                    title: 'Submit Performance Evaluation?',
                    text: 'Please confirm that all ratings are correct before submitting.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0A6025',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Submit'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('savePerformance');
                    }
                });
            });

            window.addEventListener('performance-saved', () => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Performance evaluation saved successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0A6025'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("panel.dashboard") }}';
                    }
                });
            });
        }
    }">
    </div>
</div>