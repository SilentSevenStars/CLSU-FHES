<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-6 animate-fadeIn">
                <h1 class="text-4xl font-extrabold text-[#0A6025] mb-2">
                    Experience Evaluation
                </h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Evaluate applicant's credentials and experiences
                </p>
            </div>

            <!-- Applicant Details Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 hover:shadow-lg transition-shadow duration-200 cursor-pointer border-l-4 border-blue-600"
                wire:click="toggleApplicantModal">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
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
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">II. Entry Credentials and Related Experiences</h2>
                    </div>
                </div>

                <div class="p-8">

                    <form wire:submit.prevent="confirmSubmission">
                        <div class="space-y-6">
                            <!-- Table Header -->
                            <div class="grid grid-cols-12 gap-4 pb-4 border-b-2 border-gray-300">
                                <div class="col-span-7"></div>
                                <div class="col-span-2 text-center">
                                    <span class="font-bold text-lg">Maximum points</span>
                                </div>
                                <div class="col-span-3 text-center">
                                    <span class="font-bold text-lg">Earned points</span>
                                </div>
                            </div>

                            <!-- 1. Educational Qualification -->
                            <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                                <div class="col-span-7">
                                    <span class="font-semibold">1. Educational Qualification (based on NCC
                                        Criteria)</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-semibold">85</span>
                                </div>
                                <div class="col-span-3">
                                    <input type="number" wire:model.live="education_qualification" min="0" max="85"
                                        step="0.01" placeholder="Input here"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] transition-all">
                                    @error('education_qualification')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 2. Academic/Administrative Experience -->
                            <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                                <div class="col-span-7">
                                    <span class="font-semibold">2. Academic/ Administrative and
                                        Industrial/Agricultural/Teaching</span>
                                    <div class="text-sm text-gray-600 mt-2">
                                        <div class="mb-2">
                                            <span class="font-medium">a. Applicant for College Level</span>
                                            <ul class="list-disc ml-6 mt-1">
                                                <li>Teaching Experience in College - 1 pt/yr</li>
                                                <li>Teaching Experience in High School - 1 pt/yr</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <span class="font-medium">b. Applicant for HS Level</span>
                                            <ul class="list-disc ml-6 mt-1">
                                                <li>Teaching Experience in High School - 1 pt/yr</li>
                                                <li>Teaching Experience in College - 0.5 pt/yr</li>
                                                <li>Industrial/Agricultural/Research - 1 pt/yr</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-semibold">25</span>
                                </div>
                                <div class="col-span-3">
                                    <input type="number" wire:model.live="experience_type" min="0" max="25" step="0.01"
                                        placeholder="Input here"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] transition-all">
                                    @error('experience_type')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 3. Passing Licensure Examination -->
                            <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                                <div class="col-span-7">
                                    <span class="font-semibold">3. Passing appropriate Licensure Examination</span>
                                    <div class="text-sm text-gray-600 mt-1">
                                        National Certification (NC II) - 3
                                    </div>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-semibold">5</span>
                                </div>
                                <div class="col-span-3">
                                    <input type="number" wire:model.live="licensure_examination" min="3" max="5"
                                        step="0.01" placeholder="Input here" value="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] transition-all">
                                    @error('licensure_examination')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 4. Place in Board Examination -->
                            <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                                <div class="col-span-7">
                                    <span class="font-semibold">4. Place in Board Examination</span>
                                    <div class="text-sm text-gray-600 mt-1">
                                        • 1st Place - 10 pts<br>
                                        • 2nd Place - 8 pts<br>
                                        • 3rd to 20th Place - 5 pts
                                    </div>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-semibold">10</span>
                                </div>
                                <div class="col-span-3">
                                    <select wire:model.live="place_board_exam"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] appearance-none bg-white transition-all">
                                        @foreach($placeBoardExamOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('place_board_exam')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 5. Professional Activities -->
                            <div class="grid grid-cols-12 gap-4 items-start py-4 border-b">
                                <div class="col-span-7">
                                    <span class="font-semibold">5. Participation in Professional activities such as
                                        seminar workshops and trainings</span>
                                    <div class="text-sm text-gray-600 mt-1">
                                        • 1 point for every 8 hours attendance
                                    </div>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-semibold">15</span>
                                </div>
                                <div class="col-span-3">
                                    <input type="number" wire:model.live="professional_activities" min="0" max="15"
                                        step="1" placeholder="Input here"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] transition-all">
                                    @error('professional_activities')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 6. Academic Performance -->
                            <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                                <div class="col-span-7">
                                    <span class="font-semibold">6. Academic Performance</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-semibold">10</span>
                                </div>
                                <div class="col-span-3">
                                    <select wire:model.live="academic_performance"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] appearance-none bg-white transition-all">
                                        @foreach($academicPerformanceOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('academic_performance')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 7. Publications -->
                            <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                                <div class="col-span-7">
                                    <span class="font-semibold">7. Publications (Scopus indexed)</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-semibold">10</span>
                                </div>
                                <div class="col-span-3">
                                    <select wire:model.live="publication"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] appearance-none bg-white transition-all">
                                        @foreach($publicationOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('publication')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 8. School Graduated from -->
                            <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                                <div class="col-span-7">
                                    <span class="font-semibold">8. School Graduated from</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-lg font-semibold">15</span>
                                </div>
                                <div class="col-span-3">
                                    <select wire:model.live="school_graduate"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] appearance-none bg-white transition-all">
                                        @foreach($schoolGraduateOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('school_graduate')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section Total Score Display -->
                            <div
                                class="grid grid-cols-12 gap-4 items-center py-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg px-4 border-l-4 border-gray-400">
                                <div class="col-span-7">
                                    <span class="font-bold text-lg text-gray-800">Section Total Score</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-xl font-bold text-gray-700">175</span>
                                </div>
                                <div class="col-span-3 text-center">
                                    <span class="text-xl font-bold text-gray-800">{{ number_format($totalScore, 2)
                                        }}</span>
                                </div>
                            </div>

                            <!-- Overall Total Score Display -->
                            @if($totalScore > 0)
                            <div
                                class="grid grid-cols-12 gap-4 items-center py-6 bg-gradient-to-r from-[#0A6025]/10 to-green-50 rounded-lg px-4 border-l-4 border-[#0A6025] mt-4">
                                <div class="col-span-7">
                                    <span class="font-bold text-2xl text-[#0A6025]">Overall Total Score</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-2xl font-bold text-[#0A6025]">175</span>
                                </div>
                                <div class="col-span-3 text-center">
                                    <span class="text-3xl font-bold text-[#0A6025]">{{ number_format($totalScore, 2)
                                        }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-center gap-4 mt-8">
                            <a href="{{ route('panel.dashboard') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                ← Return
                            </a>
                            <button type="submit"
                                class="bg-[#0A6025] hover:bg-[#0B712C] text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                Submit ✓
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Applicant Details Modal -->
    @if($showApplicantModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        x-data="{ show: @entangle('showApplicantModal') }" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between border-b pb-3 mb-4">
                <h3 class="text-2xl font-bold text-gray-900">Applicant Details</h3>
                <button wire:click="toggleApplicantModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
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
                        {{ collect([$applicant->street, $applicant->barangay, $applicant->city, $applicant->province,
                        $applicant->region])->filter()->join(', ') }}
                    </p>
                </div>
                @endif

                @if($jobApplication->requirements_file)
                <div class="pt-4 border-t">
                    <p class="text-sm font-medium text-gray-500 mb-2">Requirements File</p>
                    <button type="button" wire:click="$dispatch('open-pdf-viewer')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Requirements File
                    </button>
                </div>
                @endif
            </div>

            <div class="flex justify-end mt-6 pt-4 border-t">
                <button wire:click="toggleApplicantModal"
                    class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

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
                alert('Error loading PDF file');
                this.open = false;
            } finally {
                this.loading = false;
            }
        }
    }" x-on:open-pdf-viewer.window="openPdfViewer()" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-hidden"
        style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-75" @click="open = false; pdfUrl = null;"></div>

        <!-- Modal Content -->
        <div class="relative w-full h-full flex items-center justify-center">
            <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-6xl h-screen flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Application Requirements</h3>
                    <button @click="open = false; pdfUrl = null;"
                        class="text-gray-400 hover:text-gray-600 transition p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- PDF Viewer -->
                <div class="flex-1 overflow-hidden bg-gray-100">
                    <div x-show="loading" class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                            </svg>
                            <p class="text-gray-600">Loading PDF...</p>
                        </div>
                    </div>
                    <iframe x-show="!loading && pdfUrl" :src="pdfUrl" class="w-full h-full" frameborder="0">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Handler -->
    <div x-data x-on:show-error.window="
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: $event.detail.message,
            confirmButtonColor: '#d33'
        });
    "></div>

    <!-- SweetAlert2 Integration -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('showSwalConfirm', () => {
                Swal.fire({
                    title: 'Submit Experience Evaluation?',
                    text: 'Please confirm that all scores are correct before submitting.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0A6025',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Submit'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.saveExperience();
                    }
                });
            });

            Livewire.on('evaluationSaved', () => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Experience evaluation saved successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0A6025',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('panel.dashboard') }}';
                    }
                });
            });

            Livewire.on('evaluationError', () => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to save experience evaluation. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });
            });
        });
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>