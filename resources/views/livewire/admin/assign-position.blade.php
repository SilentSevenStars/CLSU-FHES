<div class="p-6 space-y-6">

    {{-- FILTERS --}}
    <div class="flex flex-wrap gap-4 items-center">
        <input type="text" wire:model.debounce.500ms="search" placeholder="Search applicant name..."
               class="w-64 rounded-lg border-gray-300 focus:ring focus:ring-blue-200">

        <select wire:model="positionFilter" class="rounded-lg border-gray-300 focus:ring focus:ring-blue-200">
            <option value="">All Applied Positions</option>
            @foreach ($positions as $position)
                <option value="{{ $position->id }}">{{ $position->name }}</option>
            @endforeach
        </select>

        <select wire:model="perPage" class="rounded-lg border-gray-300 focus:ring focus:ring-blue-200 w-28">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
        </select>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Applicant Name</th>
                    <th class="px-4 py-3">Current Position</th>
                    <th class="px-4 py-3">Applied Position</th>
                    <th class="px-4 py-3">Interview Date</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($applications as $app)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $app->applicant->user->name }}</td>
                        <td class="px-4 py-3">{{ $app->applicant->position ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $app->position->name }}</td>
                        <td class="px-4 py-3">{{ $app->evaluation?->interview_date?->format('M d, Y') ?? 'Not set' }}</td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="openStatusModal({{ $app->id }})"
                                    class="px-3 py-1 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                Assign & Send
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">No applicants found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div>{{ $applications->links() }}</div>

    {{-- STATUS MODAL --}}
    <div x-data="{ open: @entangle('showStatusModal') }" x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.outside="open = false" class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 text-center">
            <h2 class="text-lg font-bold mb-3 capitalize">{{ $statusType }}</h2>
            <p class="text-gray-700">{{ $statusMessage }}</p>
            <button @click="$wire.closeModal()" class="mt-5 px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">OK</button>
        </div>
    </div>

</div>
