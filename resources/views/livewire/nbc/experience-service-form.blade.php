<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Experience and Length of Service Form</h1>
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
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $position->department->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <form>
            <!-- Main Experience Form -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">2.0 EXPERIENCE AND LENGTH OF SERVICE ... 25 pts.</h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Section 2.1 - Academic Experience -->
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            2.1 Academic Experience
                        </h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="space-y-3 text-sm text-gray-700">
                                <div class="pl-4">
                                    <div class="font-medium">2.1.1 For every year of full-time academic service</div>
                                    <div class="pl-6 text-gray-600">in a state institution of higher learning</div>
                                </div>
                                <div class="pl-4">
                                    <div class="font-medium">2.1.2 for every year of full-time academic service</div>
                                    <div class="pl-6 text-gray-600">in the institution of higher learning other than</div>
                                    <div class="pl-6 text-gray-600">SUC's, CHED supervised and TESDA Schools</div>
                                    <div class="pl-6 text-gray-600">service in private or public research institution</div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Fields for 2.1.1 -->
                        <div class="grid grid-cols-12 gap-4 items-center mb-3">
                            <div class="col-span-10">
                                <label class="block text-sm font-medium text-gray-700">
                                    Section 2.1.1 Score (State Institution) - RS
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input 
                                    type="number" 
                                    wire:model.live="rs_2_1_1"
                                    step="0.01"
                                    min="0"
                                    max="25"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center"
                                    placeholder="RS"
                                >
                            </div>
                        </div>
                        @error('rs_2_1_1') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror

                        <!-- Input Fields for 2.1.2 -->
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-sm font-medium text-gray-700">
                                    Section 2.1.2 Score (Other Institution) - RS
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input 
                                    type="number" 
                                    wire:model.live="rs_2_1_2"
                                    step="0.01"
                                    min="0"
                                    max="25"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center"
                                    placeholder="RS"
                                >
                            </div>
                        </div>
                        @error('rs_2_1_2') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Section 2.2 - Administrative Experience -->
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            2.2 Administrative Experience
                        </h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="pl-4 font-medium">2.2.1 For every full-time year of administrative experience as:</div>
                                <div class="pl-8">a. President</div>
                                <div class="pl-8">b. Vice President</div>
                                <div class="pl-8">c. Dean/Director/School Superintendent</div>
                                <div class="pl-8">d. Principal/Supervisor / Department Chairperson/ Head of Unit</div>
                            </div>
                        </div>

                        <!-- Input Fields for 2.2.1 -->
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-sm font-medium text-gray-700">
                                    Section 2.2.1 Score (Administrative) - RS
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input 
                                    type="number" 
                                    wire:model.live="rs_2_2_1"
                                    step="0.01"
                                    min="0"
                                    max="25"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center"
                                    placeholder="RS"
                                >
                            </div>
                        </div>
                        @error('rs_2_2_1') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Section 2.3.1 - Professional/Technical Experience -->
                    <div class="border-l-4 border-purple-500 pl-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            2.3.1 Professional/Technical Experience
                        </h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="pl-4 font-medium">For every year of full-time Professional/ and technical experience as:</div>
                                <div class="pl-8">a. Manager/Entrepreneur/ consultant</div>
                                <div class="pl-8">b. Supervisor/Head of Unit</div>
                                <div class="pl-8">c. Rank and File</div>
                            </div>
                        </div>

                        <!-- Input Fields for 2.3.1 -->
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-sm font-medium text-gray-700">
                                    Section 2.3.1 Score (Professional/Technical) - RS
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input 
                                    type="number" 
                                    wire:model.live="rs_2_3_1"
                                    step="0.01"
                                    min="0"
                                    max="25"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center"
                                    placeholder="RS"
                                >
                            </div>
                        </div>
                        @error('rs_2_3_1') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Section 2.3.2 - Public/Private Institution Experience -->
                    <div class="border-l-4 border-orange-500 pl-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            2.3.2 Public/Private Institution Experience
                        </h3>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="pl-4 font-medium">For every year of experience in the public and private institutions as:</div>
                                <div class="pl-8">a. Cooperating Teacher</div>
                                <div class="pl-8">b. Basic Education Teacher</div>
                            </div>
                        </div>

                        <!-- Input Fields for 2.3.2 -->
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-sm font-medium text-gray-700">
                                    Section 2.3.2 Score (Teaching) - RS
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input 
                                    type="number" 
                                    wire:model.live="rs_2_3_2"
                                    step="0.01"
                                    min="0"
                                    max="25"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center"
                                    placeholder="RS"
                                >
                            </div>
                        </div>
                        @error('rs_2_3_2') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Subtotal Display -->
                    <div class="border-t-2 border-gray-300 pt-4">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-8">
                                <label class="block text-lg font-bold text-gray-900">
                                    SUBTOTAL
                                </label>
                                <p class="text-xs text-gray-600 mt-1">EP = MIN(RS Subtotal, 25)</p>
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
                        wire:click="previous"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-150"
                    >
                        ← Previous
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
                            @if($existing_file_path)
                                <button 
                                    type="button"
                                    wire:click="$dispatch('open-pdf-viewer')"
                                    class="mt-1 inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View File
                                </button>
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
    }"
    x-on:open-pdf-viewer.window="openPdfViewer()"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-hidden"
    style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-75" @click="open = false; pdfUrl = null;"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full h-full flex items-center justify-center">
            <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-6xl h-screen flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Application Requirements</h3>
                    <button @click="open = false; pdfUrl = null;" 
                        class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- PDF Viewer -->
                <div class="flex-1 overflow-hidden">
                    <div x-show="loading" class="flex items-center justify-center h-full">
                        <svg class="animate-spin h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V8l-4 4 4 4V8a8 8 0 11-8 8z"></path>
                        </svg>
                    </div>
                    <iframe x-show="!loading && pdfUrl" 
                        :src="pdfUrl" 
                        class="w-full h-full"
                        frameborder="0">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>