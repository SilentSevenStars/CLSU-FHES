<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Panel Dashboard</h1>

    @if (!$panel)
        <p class="text-red-600 font-semibold">You are not registered as a panel member.</p>
        @return
    @endif

    <!-- Search -->
    <div class="mb-4">
        <input wire:model.live="search"
               type="text"
               placeholder="Search applicant..."
               class="w-full px-4 py-2 border rounded-lg">
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Applicant</th>
                    <th class="px-4 py-2 text-left">Position</th>
                    <th class="px-4 py-2 text-left">Interview Date</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($applications as $app)

                @php
                    $evaluation = $app->evaluation;
                    $assignment = $assignments[$evaluation->id] ?? null;
                    $isComplete = $assignment && $assignment->status === 'complete';
                    $panelPos = strtolower($panel->panel_position);
                @endphp

                <tr class="border-b">
                    <td class="px-4 py-2">
                        {{ $app->applicant->first_name }} {{ $app->applicant->last_name }} <br>
                        <span class="text-sm text-gray-600">{{ $app->applicant->user->email }}</span>
                    </td>

                    <td class="px-4 py-2">
                        {{ $app->position->position }}
                        <br>
                        <span class="text-sm text-gray-600">
                            {{ $app->position->name }}
                        </span>
                    </td>

                    <td class="px-4 py-2">
                        {{ \Carbon\Carbon::parse($evaluation->interview_date)->format('F d, Y') }}
                    </td>

                    <!-- STATUS -->
                    <td class="px-4 py-2">
                        @if ($isComplete)
                            <span class="px-3 py-1 bg-green-600 text-white rounded-full text-xs font-bold">
                                Completed
                            </span>
                        @else
                            <span class="px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-bold">
                                Not Yet
                            </span>
                        @endif
                    </td>

                    <!-- ACTION -->
                    <td class="px-4 py-2">
                        @if (! $isComplete)

                            @if ($panelPos === 'head')
                                <a href="{{ route('panel.experience', $evaluation->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                    Evaluate
                                </a>
                            @else
                                <a href="{{ route('panel.interview', $evaluation->id) }}"
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
                                    Evaluate
                                </a>
                            @endif

                        @else
                            <button disabled
                                class="bg-gray-400 text-white px-4 py-2 rounded-lg text-sm opacity-70 cursor-not-allowed">
                                Completed
                            </button>
                        @endif
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-600 py-4">
                        No scheduled applicants today.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
