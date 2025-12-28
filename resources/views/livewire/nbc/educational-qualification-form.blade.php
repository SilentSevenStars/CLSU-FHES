<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Educational Qualification Form</h1>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $assignment->isEvaluator() ? 'Evaluator' : 'Verifier' }} Assessment - {{ $assignment->nbcCommittee->position_name }}
                </p>
            </div>
            <button 
                wire:click="toggleApplicantModal"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150"
            >
                View Applicant Details
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                <p class="text-green-700">{{ session('message') }}</p>
            </div>
        @endif

        @if (session()->has('info'))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                <p class="text-blue-700">{{ session('info') }}</p>
            </div>
        @endif

        <!-- Applicant Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Applicant Name</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $applicant->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Position Applied</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $position->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Department</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $position->department ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="save">
            <!-- Main Qualification Form -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">1.0 EDUCATIONAL QUALIFICATION ... 85 pts.</h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Section 1.1 - Highest Relevant Academic Degree -->
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            1.1 Highest relevant academic degree or educational attainment
                        </h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-700 mb-2">
                                <div class="col-span-10">Criteria</div>
                                <div class="col-span-2 text-center">
                                    <div class="text-xs">RS</div>
                                </div>
                            </div>

                            <!-- Degree Options List -->
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="pl-4">1.1.1 Doctorate Degree</div>
                                <div class="pl-4">1.1.2 Master's Degree</div>
                                <div class="pl-4">1.1.3 LLD, MD</div>
                                <div class="pl-4">1.1.4 Diploma Course: (Above a bachelors degree)</div>
                                <div class="pl-4">1.1.5 Bachelor's Degree</div>
                                <div class="pl-8">a. Four Years</div>
                                <div class="pl-8">b. Exceeding four years</div>
                                <div class="pl-12 text-xs text-gray-600">45 plus 5 pts. For every year over 4 years</div>
                                <div class="pl-4">1.1.6 Special Courses</div>
                                <div class="pl-8">a. 3 - year post secondary course</div>
                                <div class="pl-8">b. 2 - year post secondary course</div>
                            </div>
                        </div>

                        <!-- Input Fields for 1.1 -->
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-sm font-medium text-gray-700">
                                    Section 1.1 Score (RS)
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input 
                                    type="number" 
                                    wire:model.live="rs_1_1"
                                    step="0.01"
                                    min="0"
                                    max="85"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center"
                                    placeholder="RS"
                                >
                            </div>
                        </div>
                        @error('rs_1_1') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Section 1.2 - Additional Equivalent Degree -->
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            1.2 Additional equivalent degree earned related to the present position
                        </h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="pl-4">1.2.1 Master's Degree</div>
                                <div class="pl-4">1.2.2 Bachelor's Degree</div>
                            </div>
                        </div>

                        <!-- Input Fields for 1.2 -->
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-sm font-medium text-gray-700">
                                    Section 1.2 Score (RS)
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input 
                                    type="number" 
                                    wire:model.live="rs_1_2"
                                    step="0.01"
                                    min="0"
                                    max="85"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center"
                                    placeholder="RS"
                                >
                            </div>
                        </div>
                        @error('rs_1_2') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Section 1.3 - Additional Credits -->
                    <div class="border-l-4 border-purple-500 pl-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            1.3 Additional credits earned (Maximum of 10 points)
                        </h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="pl-4">1.3.1 For every 3 unit credit earned towards</div>
                                <div class="pl-8">an approved higher degree course</div>
                            </div>
                        </div>

                        <!-- Input Fields for 1.3 -->
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-sm font-medium text-gray-700">
                                    Section 1.3 Score (RS)
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input 
                                    type="number" 
                                    wire:model.live="rs_1_3"
                                    step="0.01"
                                    min="0"
                                    max="10"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center"
                                    placeholder="RS"
                                >
                            </div>
                        </div>
                        @error('rs_1_3') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Subtotal Display -->
                    <div class="border-t-2 border-gray-300 pt-4">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-8">
                                <label class="block text-lg font-bold text-gray-900">
                                    SUBTOTAL
                                </label>
                            </div>
                            <div class="col-span-4 grid grid-cols-2 gap-2">
                                <div>
                                    <div class="text-xs text-center text-gray-600 mb-1">RS Total</div>
                                    <div class="px-3 py-2 bg-blue-100 border border-blue-300 rounded-lg text-center font-semibold">
                                        {{ number_format($this->rsSubtotal, 2) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-center text-gray-600 mb-1">EP</div>
                                    <div class="px-3 py-2 bg-green-100 border border-green-300 rounded-lg text-center font-semibold">
                                        {{ number_format($this->epSubtotal, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                    <button 
                        type="button"
                        wire:click="return"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150"
                    >
                        ← Return to Dashboard
                    </button>
                    <button 
                        type="button"
                        wire:click="next"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-150"
                    >
                        Next →
                    </button>
                </div>
            </div>
        </form>
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
                <!-- Modal Header -->
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

                <!-- Modal Content -->
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
                        <div class="col-span-2">
                            <p class="text-sm font-medium text-gray-500">Address</p>
                            <p class="mt-1 text-base text-gray-900">
                                {{ $applicant->street }}, {{ $applicant->barangay }}, {{ $applicant->city }}, {{ $applicant->province }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Present Position</p>
                            <p class="mt-1 text-base text-gray-900">{{ $jobApplication->present_position }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Education</p>
                            <p class="mt-1 text-base text-gray-900">{{ $jobApplication->education }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Experience (Years)</p>
                            <p class="mt-1 text-base text-gray-900">{{ $jobApplication->experience }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Eligibility</p>
                            <p class="mt-1 text-base text-gray-900">{{ $jobApplication->eligibility }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm font-medium text-gray-500">Requirements File</p>
                            @if($jobApplication->requirements_file)
                                <a 
                                    href="{{ Storage::url($jobApplication->requirements_file) }}" 
                                    target="_blank"
                                    class="mt-1 inline-flex items-center text-blue-600 hover:text-blue-800"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    View Requirements Document
                                </a>
                            @else
                                <p class="mt-1 text-base text-gray-500">No file uploaded</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
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
</div>