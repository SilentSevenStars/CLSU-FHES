<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-700">Applicant Dashboard</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white shadow rounded-lg p-4 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Total Applications</p>
            <h2 class="text-2xl font-bold">{{ $applications->count() }}</h2>
        </div>

        <div class="bg-white shadow rounded-lg p-4 border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Pending</p>
            <h2 class="text-2xl font-bold">
                {{ $applications->where('status', 'pending')->count() }}
            </h2>
        </div>

        <div class="bg-white shadow rounded-lg p-4 border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Approved</p>
            <h2 class="text-2xl font-bold">
                {{ $applications->where('status', 'approve')->count() }}
            </h2>
        </div>

        <div class="bg-white shadow rounded-lg p-4 border-l-4 border-red-500">
            <p class="text-gray-500 text-sm">Declined</p>
            <h2 class="text-2xl font-bold">
                {{ $applications->where('status', 'decline')->count() }}
            </h2>
        </div>

    </div>

    <!-- Application List -->
    <div class="bg-white shadow rounded-lg p-6 mt-4">
        <h2 class="text-xl font-semibold mb-4">Your Applications</h2>

        <div wire:poll.10s="loadApplications">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium">Position</th>
                            <th class="px-4 py-2 text-left text-sm font-medium">Status & Progress</th>
                            <th class="px-4 py-2 text-left text-sm font-medium">Submitted</th>
                            <th class="px-4 py-2 text-left text-sm font-medium">Requirements</th>
                            <th class="px-4 py-2 text-left text-sm font-medium">Interview Schedule</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($applications as $app)
                        <tr>

                            <!-- Position -->
                            <td class="px-4 py-3">
                                <span class="font-medium">{{ $app->position->name }}</span>
                                <p class="text-xs text-gray-500">{{ $app->position->department }}</p>
                            </td>

                            <!-- Status + Progress -->
                            <td class="px-4 py-3">
                                @switch($app->status)
                                    @case('pending')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Pending</span>
                                        @break
                                    @case('approve')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">Approved</span>
                                        @break
                                    @case('decline')
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">Declined</span>
                                        @break
                                    @case('hired')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">Hired</span>
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

                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-indigo-600 h-2 rounded-full"
                                        style="width: {{ ($currentStep / 3) * 100 }}%"></div>
                                </div>

                                <p class="text-xs text-gray-600 mt-1">
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
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $app->created_at->format('F d, Y') }}
                            </td>

                            <!-- Requirements -->
                            <td class="px-4 py-3">
                                @if($app->requirements_file)
                                    <a
                                        href="{{ asset($app->requirements_file) }}"
                                        target="_blank"
                                        class="text-blue-600 underline">
                                        View File
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">No file uploaded</span>
                                @endif
                            </td>

                            <!-- Interview -->
                            <td class="px-4 py-3">
                                @if($app->interview_date)
                                    <div class="bg-blue-50 p-2 rounded-lg border border-blue-200 text-xs">
                                        <p><strong>Date:</strong> {{ $app->interview_date }}</p>
                                        <p><strong>Time:</strong> {{ $app->interview_time }}</p>
                                        <p><strong>Location:</strong> {{ $app->interview_location }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-xs">No schedule</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                No applications yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
