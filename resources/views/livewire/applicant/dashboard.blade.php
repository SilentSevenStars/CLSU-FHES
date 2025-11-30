<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold text-[#0A6025] mb-2">
                            Applicant Dashboard
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            View and track your job applications
                        </p>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-[#0A6025] transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Applications</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                {{ $applications->count() }}
                            </h3>
                        </div>
                        <div class="bg-[#0A6025] rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-yellow-500 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Pending</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                {{ $applications->where('status', 'pending')->count() }}
                            </h3>
                        </div>
                        <div class="bg-yellow-500 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-[#0A6025] transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Approved</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                {{ $applications->where('status', 'approve')->count() }}
                            </h3>
                        </div>
                        <div class="bg-[#0A6025] rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-red-500 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Declined</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                {{ $applications->where('status', 'decline')->count() }}
                            </h3>
                        </div>
                        <div class="bg-red-500 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application List -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                <!-- Table Header -->
                <div class="bg-[#0A6025] p-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <i class="fa-solid fa-briefcase text-white text-lg"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Your Applications</h2>
                    </div>
                </div>

                <div wire:poll.10s="loadApplications">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800">Position</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800">Status & Progress</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800">Requirements</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-800">Interview Schedule</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($applications as $app)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">

                                    <!-- Position -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-semibold text-gray-900">{{ $app->position->name }}</span>
                                        <p class="text-xs text-gray-500 mt-1">{{ $app->position->department }}</p>
                                    </td>

                                    <!-- Status + Progress -->
                                    <td class="px-6 py-4">
                                        @switch($app->status)
                                            @case('pending')
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                                                @break
                                            @case('approve')
                                                <span class="px-3 py-1 bg-[#0A6025] bg-opacity-10 text-[#0A6025] rounded-full text-xs font-semibold">Approved</span>
                                                @break
                                            @case('decline')
                                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Declined</span>
                                                @break
                                            @case('hired')
                                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Hired</span>
                                                @break
                                        @endswitch

                                        @php
                                            $steps = [
                                                'pending' => 1,
                                                'approve' => 2,
                                                'decline' => 2,
                                                'hired' => 3
                                            ];

                                            $currentStep = $steps[$app->status];
                                        @endphp

                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                                            <div class="bg-[#0A6025] h-2 rounded-full transition-all duration-300"
                                                style="width: {{ ($currentStep / 3) * 100 }}%"></div>
                                        </div>

                                        <p class="text-xs text-gray-600 mt-2">
                                            @if($app->status === 'pending')
                                                Under Review
                                            @elseif($app->status === 'approve')
                                                Approved / For Interview
                                            @elseif($app->status === 'decline')
                                                Declined
                                            @elseif($app->status === 'hired')
                                                Hired
                                            @endif
                                        </p>
                                    </td>

                                    <!-- Submitted -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $app->created_at->format('F d, Y') }}
                                    </td>

                                    <!-- Requirements -->
                                    <td class="px-6 py-4">
                                        @if($app->requirements_file)
                                            <a
                                                href="{{ asset($app->requirements_file) }}"
                                                target="_blank"
                                                class="text-[#0A6025] hover:text-[#0B712C] underline font-medium text-sm transition-colors">
                                                View File
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm">No file uploaded</span>
                                        @endif
                                    </td>

                                    <!-- Interview -->
                                    <td class="px-6 py-4">
                                        @if($app->interview_date)
                                            <div class="bg-[#0A6025] bg-opacity-5 p-3 rounded-lg border border-[#0A6025] border-opacity-20 text-xs">
                                                <p class="mb-1"><strong class="text-gray-700">Date:</strong> <span class="text-gray-600">{{ $app->interview_date }}</span></p>
                                                <p class="mb-1"><strong class="text-gray-700">Time:</strong> <span class="text-gray-600">{{ $app->interview_time }}</span></p>
                                                <p><strong class="text-gray-700">Location:</strong> <span class="text-gray-600">{{ $app->interview_location }}</span></p>
                                            </div>
                                        @else
                                            <span class="text-gray-500 text-sm">No schedule</span>
                                        @endif
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <p class="text-gray-500 text-lg font-medium">No applications yet.</p>
                                            <p class="text-gray-400 text-sm mt-1">Start applying for positions to see them here.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
