<div x-data="{ showModal: @entangle('showModal') }">
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Success Message -->
            @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg shadow animate-fadeIn">
                {{ session('success') }}
            </div>
            @endif

            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    @php
                    use Illuminate\Support\Facades\Storage;
                    @endphp

                    <div>
                        <h1 class="text-4xl font-extrabold text-green-800 mb-2">
                            Available Positions
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <span class="text-xl">💼</span>
                            Browse and apply for available job positions
                        </p>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-6 animate-fadeIn">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <span class="text-gray-400 text-xl">🔍</span>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-green-700 focus:border-green-700 sm:text-sm transition duration-150 ease-in-out shadow-sm"
                        placeholder="Search by position, department, college, or specialization..." />
                </div>
            </div>

            @if($positions->isEmpty())

            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-xl p-12 text-center animate-fadeIn">
                <div class="max-w-md mx-auto">
                    <div class="bg-gray-100 rounded-full p-6 w-24 h-24 mx-auto mb-4 flex items-center justify-center">
                        <span class="text-5xl">💼</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Available Positions</h3>
                    <p class="text-gray-500">
                        @if(!empty($search))
                        No positions match your search criteria. Try different keywords.
                        @else
                        There are no job positions available at the moment. Please check back later.
                        @endif
                    </p>
                </div>
            </div>

            @else

            <!-- Positions Grid -->
            <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-8">
                @foreach($positions as $index => $position)
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 animate-slideInLeft border-l-4 border-green-700"
                    style="animation-delay: {{ $index * 0.1 }}s;">
                    <div class="p-6">

                        <!-- Icon -->
                        <div class="bg-yellow-400 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300 w-16 h-16 flex items-center justify-center mb-4">
                            <span class="text-3xl">💼</span>
                        </div>

                        <!-- Job Title with Department -->
                        <h5 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-green-700 transition-colors duration-300 leading-tight">
                            {{ $position->name }} - {{ $position->department->name }}
                        </h5>

                        <!-- College -->
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xl">🏛️</span>
                            <p class="text-sm font-medium text-gray-600">{{ $position->college->name }}</p>
                        </div>

                        <!-- Date Range -->
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xl">📅</span>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($position->start_date)->format('M d, Y') }} -
                                {{ \Carbon\Carbon::parse($position->end_date)->format('M d, Y') }}
                            </p>
                        </div>

                        <!-- View Details Button -->
                        <button wire:click="viewDetails({{ $position->id }})"
                            class="block w-full text-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-semibold rounded-lg text-sm px-4 py-3 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                            View Details
                        </button>

                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <!-- Background Overlay -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                @if($selectedPosition)
                <!-- Modal Header (Fixed) -->
                <div class="bg-green-700 px-6 py-4 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-white" id="modal-title">
                            {{ $selectedPosition->name }}
                        </h3>
                        <button @click="$wire.closeModal()"
                            class="text-white hover:text-gray-200 transition-colors duration-200 text-2xl font-bold leading-none">
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                    <div class="space-y-6">

                        <!-- Department & College -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">Department</h4>
                                <p class="text-lg font-medium text-gray-800">{{ $selectedPosition->department->name }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">College</h4>
                                <p class="text-lg font-medium text-gray-800">{{ $selectedPosition->college->name }}</p>
                            </div>
                        </div>

                        <!-- Status & Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">Status</h4>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $selectedPosition->status === 'vacant' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($selectedPosition->status) }}
                                </span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">Start of Application</h4>
                                <p class="text-base font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($selectedPosition->start_date)->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-500 mb-1">End of Application</h4>
                                <p class="text-base font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($selectedPosition->end_date)->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Specialization -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-semibold text-gray-500 mb-2">Specialization</h4>
                            <p class="text-base text-gray-800">{{ $selectedPosition->specialization }}</p>
                        </div>

                        <!-- Requirements Section -->
                        <div class="border-t pt-6">
                            <h4 class="text-xl font-bold text-gray-800 mb-4">Requirements</h4>

                            <div class="space-y-4">
                                <!-- Education -->
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl mt-1">📚</span>
                                    <div>
                                        <h5 class="font-semibold text-gray-800">Education</h5>
                                        <p class="text-gray-600">{{ $selectedPosition->education }}</p>
                                    </div>
                                </div>

                                <!-- Experience -->
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl mt-1">💼</span>
                                    <div>
                                        <h5 class="font-semibold text-gray-800">Experience</h5>
                                        <p class="text-gray-600">{{ $selectedPosition->experience }} years of relevant experience</p>
                                    </div>
                                </div>

                                <!-- Training -->
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl mt-1">✅</span>
                                    <div>
                                        <h5 class="font-semibold text-gray-800">Training</h5>
                                        <p class="text-gray-600">{{ $selectedPosition->training }} hours of training required</p>
                                    </div>
                                </div>

                                <!-- Eligibility -->
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl mt-1">📄</span>
                                    <div>
                                        <h5 class="font-semibold text-gray-800">Eligibility</h5>
                                        <p class="text-gray-600">{{ $selectedPosition->eligibility }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal Footer (Fixed) -->
                <div class="bg-gray-50 px-6 py-4 sticky bottom-0 border-t border-gray-200">
                    <div class="flex items-center justify-end gap-3">
                        <button @click="$wire.closeModal()"
                            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-colors duration-200">
                            Close
                        </button>

                        @if(in_array($selectedPosition->id, $applied))
                        @if($this->canEditApplication($selectedPosition->id))
                        <!-- Edit Application Button -->
                        <a href="{{ route('edit-job-application', ['application_id' => $this->getApplicationId($selectedPosition->id)]) }}"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            Edit Application
                        </a>
                        @else
                        <!-- Already Applied (Can't Edit) -->
                        <button disabled
                            class="px-6 py-2.5 text-sm font-semibold text-gray-500 bg-gray-300 rounded-lg cursor-not-allowed">
                            Application Submitted
                        </button>
                        @endif
                        @else
                        <!-- Apply Now Button -->
                        <a href="{{ route('job-application', ['position_id' => $selectedPosition->id]) }}"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-green-700 rounded-lg hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            Apply Now
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }

        .animate-slideInLeft {
            animation: slideInLeft 0.5s ease-out;
        }
    </style>
</div>