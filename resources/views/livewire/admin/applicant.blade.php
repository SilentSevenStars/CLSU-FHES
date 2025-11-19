<div class="flex-1 p-6 overflow-y-auto bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 shadow-lg rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Applicant</h2>
                    <p class="text-indigo-100">Review and manage applicant</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">

            <!-- Pending -->
            <div class="p-6 bg-yellow-100 border-l-4 border-yellow-500 rounded-lg shadow">
                <h2 class="text-xl font-bold text-yellow-800">Pending This Month</h2>
                <p class="text-3xl font-bold mt-2 text-yellow-900">{{ $pendingCount }}</p>
            </div>

            <!-- Approved -->
            <div class="p-6 bg-green-100 border-l-4 border-green-600 rounded-lg shadow">
                <h2 class="text-xl font-bold text-green-800">Approved This Month</h2>
                <p class="text-3xl font-bold mt-2 text-green-900">{{ $approvedCount }}</p>
            </div>

            <!-- Declined -->
            <div class="p-6 bg-red-100 border-l-4 border-red-600 rounded-lg shadow">
                <h2 class="text-xl font-bold text-red-800">Declined This Month</h2>
                <p class="text-3xl font-bold mt-2 text-red-900">{{ $declinedCount }}</p>
            </div>

        </div>

        <!-- Applications Table -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">All Applicants</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Total: {{ $pendingCount }}</span>
                    </div>
                </div>
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
                                Position
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Date Applied
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Actions
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
                                    {{ $application->created_at->format('M j, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $application->status === 'declined' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($application->status) }}
                                </span>

                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.applicant.show', $application) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Review
                                </a>
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
                                <p class="text-gray-500 text-lg font-medium">No applicant applications found</p>
                                <p class="text-gray-400 text-sm mt-1">Applications will appear here once submitted</p>
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