<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Scheduled Applicants
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            List of scheduled applicant interviews
                        </p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.2s;">

                <!-- Table Header with Filters -->
                <div class="bg-[#0a6025] p-6">
                    <div class="flex flex-col gap-4">

                        <!-- Title + Print Button -->
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                    <i class="fa-solid fa-calendar-check text-white text-lg"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-white">Scheduled Applicant List</h2>
                            </div>

                            @if($selectedPositionName)
                            <button
                                wire:click="print"
                                wire:loading.attr="disabled"
                                class="flex items-center gap-2 bg-white text-[#0a6025] hover:bg-green-50 font-semibold rounded-lg px-4 py-2 text-sm transition-colors shadow disabled:opacity-60">
                                <span wire:loading.remove wire:target="print">
                                    <i class="fa-solid fa-print mr-1"></i> Print List
                                </span>
                                <span wire:loading wire:target="print" style="display:none;">
                                    <i class="fa-solid fa-spinner fa-spin mr-1"></i> Preparing...
                                </span>
                            </button>
                            @endif
                        </div>

                        <!-- Cascading Filters: Position → College → Department → Interview Date -->
                        <div class="flex flex-wrap items-center gap-3">

                            <!-- 1. Position (always visible) -->
                            <select wire:model.live="selectedPositionName"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="">Filter by Position</option>
                                @foreach($positionNames as $name)
                                    <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>

                            <!-- 2. College (visible once a position is selected) -->
                            @if($selectedPositionName)
                            <select wire:model.live="selectedCollegeId"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="">All Colleges</option>
                                @foreach($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                            @endif

                            <!-- 3. Department (visible once a college is selected) -->
                            @if($selectedPositionName && $selectedCollegeId)
                            <select wire:model.live="selectedDepartmentId"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @endif

                            <!-- 4. Interview Date (visible only after position + college + department are selected) -->
                            @if($selectedPositionName && $selectedCollegeId && $selectedDepartmentId && $availableDates->isNotEmpty())
                            <select wire:model.live="selectedDate"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="">All Interview Dates</option>
                                @foreach($availableDates as $date)
                                    <option value="{{ $date }}">
                                        {{ \Carbon\Carbon::parse($date)->format('M j, Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">

                                    {{-- Flash Messages --}}
                                    @if(session()->has('success'))
                                    <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                        <p class="text-sm text-green-800 leading-5 tracking-wide">{{ session('success') }}</p>
                                    </div>
                                    @endif

                                    @if(session()->has('error'))
                                    <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm text-red-800 leading-5 tracking-wide">{{ session('error') }}</p>
                                    </div>
                                    @endif

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Applicant Name</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Email</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Applied Position</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">College</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Department</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Interview Scheduled</span>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            @forelse ($applications as $application)
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    <div class="text-sm font-semibold text-black">
                                                        {{ $application->applicant->user->name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        {{ $application->applicant->user->email }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        {{ $application->position->name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        {{ $application->position->college->name ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        {{ $application->position->department->name ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        @if($application->evaluation && $application->evaluation->interview_date)
                                                            {{ \Carbon\Carbon::parse($application->evaluation->interview_date)->format('M j, Y') }}
                                                        @else
                                                            <span class="text-gray-500">Not scheduled</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center">
                                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p class="text-black text-lg font-medium">No scheduled applicants found</p>
                                                    <p class="text-gray-600 text-sm mt-1">
                                                        @if($selectedPositionName)
                                                            No applicants scheduled for this position yet
                                                        @else
                                                            Please select a position to view scheduled applicants
                                                        @endif
                                                    </p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <!-- End Table -->

                                    @if($applications->hasPages())
                                    <div class="p-4">
                                        {{ $applications->links() }}
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- JS: receive rendered HTML from Livewire and open it in a new tab for printing --}}
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
</div>