<div class="min-h-screen bg-gray-50 py-8"
    x-data="{
        showSubmitModal: false,
        showSaveModal: false
    }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">NBC Evaluation Form</h1>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $assignment->isEvaluator() ? 'Evaluator' : 'Verifier' }} Assessment
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
            <!-- Main NBC Form -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">NBC Evaluation Scores</h2>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Educational Qualification Score -->
                    <div class="border-l-4 border-blue-500 pl-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Educational Qualification
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Maximum: 85 points
                                </p>
                            </div>
                            <div class="w-48">
                                <input 
                                    type="number" 
                                    wire:model.live="educational_qualification"
                                    step="0.001"
                                    min="0"
                                    max="85"
                                    class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center font-semibold"
                                    placeholder="0.000"
                                >
                            </div>
                        </div>
                        @error('educational_qualification') 
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Experience Score -->
                    <div class="border-l-4 border-green-500 pl-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Experience and Length of Service
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Maximum: 25 points
                                </p>
                            </div>
                            <div class="w-48">
                                <input 
                                    type="number" 
                                    wire:model.live="experience"
                                    step="0.001"
                                    min="0"
                                    max="25"
                                    class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-center font-semibold"
                                    placeholder="0.000"
                                >
                            </div>
                        </div>
                        @error('experience') 
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Professional Development Score -->
                    <div class="border-l-4 border-purple-500 pl-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Professional Development, Achievement & Honors
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Maximum: 90 points
                                </p>
                            </div>
                            <div class="w-48">
                                <input 
                                    type="number" 
                                    wire:model.live="professional_development"
                                    step="0.001"
                                    min="0"
                                    max="90"
                                    class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-center font-semibold"
                                    placeholder="0.000"
                                >
                            </div>
                        </div>
                        @error('professional_development') 
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Total Score Display -->
                    <div class="border-t-2 border-gray-300 pt-6 mt-8">
                        <div class="bg-indigo-50 rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        TOTAL SCORE
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Maximum: 200 points (85 + 25 + 90)
                                    </p>
                                </div>
                                <div class="px-8 py-4 bg-indigo-100 border-2 border-indigo-300 rounded-lg text-center">
                                    <div class="text-4xl font-bold text-indigo-900">
                                        {{ number_format($this->totalScore, 3) }}
                                    </div>
                                    <div class="text-xs text-indigo-700 mt-1">points</div>
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
                    <div class="flex gap-3">
                        <button 
                            type="button"
                            @click="showSaveModal = true"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-150"
                        >
                            Save Progress
                        </button>
                        <button 
                            type="button"
                            @click="showSubmitModal = true"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150"
                        >
                            Submit Evaluation
                        </button>
                    </div>
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

    <!-- Save Progress Confirmation Modal -->
    <div 
        x-show="showSaveModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="showSaveModal = false"></div>
        
        <!-- Modal -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                class="relative bg-white rounded-lg shadow-xl max-w-md w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                @click.away="showSaveModal = false"
            >
                <!-- Icon -->
                <div class="pt-8 pb-4 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                        <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="px-6 pb-4 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Save Progress?
                    </h3>
                    <p class="text-gray-600 mb-4">
                        This will save your current scores. You can continue editing later.
                    </p>
                    <div class="bg-gray-50 rounded-lg p-4 text-left">
                        <p class="text-sm font-medium text-gray-700 mb-2">Current Scores:</p>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>Educational Qualification: <span class="font-semibold">{{ number_format($educational_qualification, 3) }}</span> / 85</p>
                            <p>Experience: <span class="font-semibold">{{ number_format($experience, 3) }}</span> / 25</p>
                            <p>Professional Development: <span class="font-semibold">{{ number_format($professional_development, 3) }}</span> / 90</p>
                            <p class="pt-2 border-t border-gray-300 font-semibold text-gray-900">Total: {{ number_format($this->totalScore, 3) }} / 200</p>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="px-6 pb-6 flex gap-3">
                    <button
                        @click="showSaveModal = false"
                        class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="save"
                        @click="showSaveModal = false"
                        class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                    >
                        Yes, Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Evaluation Confirmation Modal -->
    <div 
        x-show="showSubmitModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="showSubmitModal = false"></div>
        
        <!-- Modal -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                class="relative bg-white rounded-lg shadow-xl max-w-md w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                @click.away="showSubmitModal = false"
            >
                <!-- Icon -->
                <div class="pt-8 pb-4 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                        <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="px-6 pb-4 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Submit Evaluation?
                    </h3>
                    <p class="text-gray-600 mb-4">
                        This will mark the evaluation as <strong>complete</strong>. Please review your scores before submitting.
                    </p>
                    <div class="bg-blue-50 rounded-lg p-4 text-left border-2 border-blue-200">
                        <p class="text-sm font-medium text-gray-700 mb-2">Final Scores:</p>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>Educational Qualification: <span class="font-semibold">{{ number_format($educational_qualification, 3) }}</span> / 85</p>
                            <p>Experience: <span class="font-semibold">{{ number_format($experience, 3) }}</span> / 25</p>
                            <p>Professional Development: <span class="font-semibold">{{ number_format($professional_development, 3) }}</span> / 90</p>
                            <p class="pt-2 border-t border-blue-300 font-bold text-blue-900 text-base">Total: {{ number_format($this->totalScore, 3) }} / 200</p>
                        </div>
                    </div>
                    <p class="text-sm text-red-600 mt-4 font-medium">
                        ⚠️ Once submitted, you cannot edit this evaluation.
                    </p>
                </div>
                
                <!-- Actions -->
                <div class="px-6 pb-6 flex gap-3">
                    <button
                        @click="showSubmitModal = false"
                        class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="submit"
                        @click="showSubmitModal = false"
                        class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Yes, Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>