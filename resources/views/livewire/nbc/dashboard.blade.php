<div
    x-data="{
        showChoiceModal: false,
        selectedEvaluationId: null
    }"
>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $nbcCommittee = \App\Models\NbcCommittee::where('user_id', auth()->id())->first();
        @endphp

        @if(!$nbcCommittee)
            <!-- Unauthorized Access Message -->
            <div class="flex items-center justify-center min-h-[70vh]">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100">
                        <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h2 class="mt-6 text-3xl font-bold text-gray-900">Access Denied</h2>
                    <p class="mt-2 text-lg text-gray-600">You are not registered as an NBC Committee member.</p>
                    <p class="mt-2 text-sm text-gray-500">Please contact the administrator if you believe this is an error.</p>
                    <div class="mt-8">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Authorized Content -->

            <!-- Header -->
            <div class="mb-8 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-[#0A6025] mb-1">NBC Dashboard</h1>
                    <p class="text-gray-600 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9v10a2 2 0 01-2 2z" />
                        </svg>
                        <span>View and manage NBC evaluation assignments</span>
                    </p>
                </div>

                <!-- Print Report Button -->
                <button
                    wire:click="printReport"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg text-sm transition-colors focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="printReport">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Print Report
                    </span>
                    <span wire:loading wire:target="printReport" style="display:none;">
                        <svg class="w-4 h-4 mr-1 inline animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Preparing...
                    </span>
                </button>
            </div>

            <!-- Flash: print error -->
            @if(session()->has('print_error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
                    {{ session('print_error') }}
                </div>
            @endif

            <!-- Infographics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Pending Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Pending Evaluations</p>
                            <p class="mt-2 text-4xl font-bold text-gray-900">{{ $pendingTodayCount }}</p>
                            <p class="mt-1 text-sm text-gray-500">Evaluations awaiting completion</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Complete Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Completed Evaluations</p>
                            <p class="mt-2 text-4xl font-bold text-gray-900">{{ $completeTodayCount }}</p>
                            <p class="mt-1 text-sm text-gray-500">Evaluations successfully completed</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="search"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Name or Position..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]"
                            >
                        </div>
                    </div>

                    <!-- Per Page -->
                    <div>
                        <label for="perPage" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                        <select
                            id="perPage"
                            wire:model.live="perPage"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]"
                        >
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Evaluations Table -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#0A6025]">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Position</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($evaluations as $evaluation)
                                @php
                                    // interview_date is the DEADLINE for submission
                                    // If today > interview_date, the deadline has passed and evaluation is locked
                                    $interviewDate = \Carbon\Carbon::parse($evaluation->interview_date)->startOfDay();
                                    $isPastDeadline = today()->gt($interviewDate);

                                    // Check assignment completion for the current NBC member
                                    $userAssignment = \App\Models\NbcAssignment::where('evaluation_id', $evaluation->id)
                                        ->whereHas('nbcCommittee', function($q) {
                                            $q->where('user_id', auth()->id());
                                        })
                                        ->first();
                                    $isComplete = $userAssignment && $userAssignment->status === 'complete';
                                @endphp

                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $evaluation->jobApplication->applicant->full_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ $evaluation->jobApplication->applicant->user->email ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $evaluation->jobApplication->position->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($isComplete)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                Complete
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($isComplete)
                                            <!-- Already completed -->
                                            <button disabled
                                                class="inline-flex items-center px-3 py-1 bg-gray-400 text-white rounded-md cursor-not-allowed opacity-60"
                                                title="Evaluation already completed">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Completed
                                            </button>

                                        @elseif($isPastDeadline)
                                            <!-- Past deadline — evaluation locked -->
                                            <button disabled
                                                class="inline-flex items-center px-3 py-1 bg-red-200 text-red-700 rounded-md cursor-not-allowed opacity-70"
                                                title="The submission deadline has passed. Evaluation is no longer allowed.">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-9V4m0 0L9 7m3-3l3 3"></path>
                                                </svg>
                                                Locked
                                            </button>

                                        @else
                                            <!-- Active — can evaluate -->
                                            <button
                                                @click="selectedEvaluationId = {{ $evaluation->id }}; showChoiceModal = true"
                                                class="inline-flex items-center px-3 py-1 bg-[#0A6025] text-white rounded-md hover:bg-[#0B712C] transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Evaluate
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="mt-4 text-sm text-gray-500">No evaluations found</p>
                                        @if($search)
                                            <button
                                                wire:click="$set('search', '')"
                                                class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                                                Clear filters
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $evaluations->links() }}
                </div>
            </div>

            <!-- Results Summary -->
            <div class="mt-4 text-sm text-gray-600 text-center">
                Showing {{ $evaluations->firstItem() ?? 0 }} to {{ $evaluations->lastItem() ?? 0 }} of {{ $evaluations->total() }} results
            </div>
        @endif
        </div>
    </div>

    <!-- Evaluation Method Choice Modal -->
    <div
        x-show="showChoiceModal"
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
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="showChoiceModal = false"></div>

        <!-- Modal -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div
                class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                @click.away="showChoiceModal = false"
            >
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-900">Choose Evaluation Method</h3>
                        <button @click="showChoiceModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Select how you would like to complete this evaluation</p>
                </div>

                <!-- Content -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Quick Input Option -->
                        <div class="group cursor-pointer">
                            <a :href="`/nbc/evaluation/${selectedEvaluationId}`" class="block h-full">
                                <div class="h-full border-2 border-indigo-200 rounded-lg p-6 hover:border-indigo-500 hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-indigo-50 to-white">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="bg-indigo-100 rounded-full p-4 mb-4 group-hover:bg-indigo-200 transition-colors">
                                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">Quick Input</h4>
                                        <p class="text-sm text-gray-600 mb-4">Directly enter scores for the three main categories</p>
                                        <div class="mt-6 w-full">
                                            <span class="block w-full px-4 py-2 bg-indigo-600 text-white rounded-lg text-center font-medium group-hover:bg-indigo-700 transition-colors">
                                                Use Quick Input
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Detailed Form Option -->
                        <div class="group cursor-pointer">
                            <a :href="`/nbc/educational-qualification/${selectedEvaluationId}`" class="block h-full">
                                <div class="h-full border-2 border-blue-200 rounded-lg p-6 hover:border-blue-500 hover:shadow-lg transition-all duration-200 bg-gradient-to-br from-blue-50 to-white">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="bg-blue-100 rounded-full p-4 mb-4 group-hover:bg-blue-200 transition-colors">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">Detailed Forms</h4>
                                        <p class="text-sm text-gray-600 mb-4">Complete comprehensive evaluation across three sections</p>
                                        <div class="mt-6 w-full">
                                            <span class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-center font-medium group-hover:bg-blue-700 transition-colors">
                                                Use Detailed Forms
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                    <p class="text-xs text-gray-500 text-center">
                        Both methods will save your evaluation to the same database. Choose the one that works best for you.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- JS: open the rendered HTML in a new tab for printing --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('openPrintTab', (event) => {
                const newTab = window.open('', '_blank');
                newTab.document.open();
                newTab.document.write(event.html);
                newTab.document.close();
            });
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>