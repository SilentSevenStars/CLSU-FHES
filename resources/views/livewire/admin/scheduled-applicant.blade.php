<div class="flex-1 p-6 overflow-y-auto bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 shadow-lg rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Scheduled Applicants</h2>
                    <p class="text-indigo-100">List of scheduled applicant interview</p>
                </div>
            </div>
        </div>


        <!-- Applications Table -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">All Applicants</h3>
                    <div>
                        <select wire:model.live="selectedPosition"
                            class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Filter by Position</option>

                            @foreach($positions as $pos)
                            <option value="{{ $pos->id }}">
                                {{ $pos->name }}
                                ({{ \Carbon\Carbon::parse($pos->start_date)->format('M j, Y') }}
                                -
                                {{ \Carbon\Carbon::parse($pos->end_date)->format('M j, Y') }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($selectedPosition)
                        <button wire:click="exportExcel"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                            Export Excel
                        </button>

                        <button wire:click="exportPDF"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                            Export PDF
                        </button>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Total: {{ $pendingCount }}</span>
                    </div>
                </div>
            </div>

            {{-- DEBUG: Show selected position and count --}}
            <div class="px-6 py-2 bg-yellow-50 border-b border-yellow-200 text-xs text-yellow-800">
                <p>DEBUG: Selected Position ID: <strong>{{ $selectedPosition ?? '(none)' }}</strong> | Total
                    Applications: <strong>{{ $pendingCount }}</strong></p>
            </div>

            {{-- Flash Messages --}}
            @if(session()->has('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800 leading-5 tracking-wide">{{ session('success') }}</p>
            </div>
            @endif

            @if(session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800 leading-5 tracking-wide">{{ session('error') }}</p>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Applicant Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Applied Position
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Interview Scheduled
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($applications as $application)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $application->applicant->user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $application->applicant->user->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $application->position->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $application->evaluation->interview_date->format('M j, Y') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No scheduled Applicants found</p>
                                <p class="text-gray-400 text-sm mt-1">Applicants will appear here once review is
                                    finished</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>