<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Screening</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">

        <!-- Filters -->
        <div class="flex items-center gap-4 mb-6">

            <!-- Search -->
            <div class="flex-1 relative">
                <input 
                    type="text"
                    wire:model.live.debounce.300ms="searchTerm"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="Search applicant"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Position Filter -->
            <div class="w-80">
                <select 
                    wire:model.live="selectedPosition"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                >
                    <option value="">Position Filter</option>
                    @foreach($positions as $position)
                        <option value="{{ $position['filter_key'] }}">
                            {{ $position['display_name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Export -->
            <button 
                wire:click="export"
                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
            >
                Export
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Name of Applicant
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Field of Specialization
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Performance
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Credentials & Experience
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Interview
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Total
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Rank
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($screeningData as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium">
                                {{ $data['name'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $data['department'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                {{ $data['performance'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                {{ $data['credentials_experience'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                {{ $data['interview'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center font-semibold">
                                {{ $data['total'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-700 font-bold">
                                    {{ $data['rank'] }}
                                </span>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                @if($selectedPosition)
                                    <p class="text-lg font-medium">No completed evaluations found</p>
                                    <p class="text-sm mt-1">All panel assignments must be completed</p>
                                @else
                                    <p class="text-lg font-medium">Please select a position</p>
                                    <p class="text-sm mt-1">Use the position filter above</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($screeningData) > 0)
            <div class="mt-4 text-sm text-gray-600 flex justify-between">
                <div>Showing {{ count($screeningData) }} result(s)</div>
                <div class="text-xs text-gray-500">
                    * Only applicants with completed panel evaluations are shown
                </div>
            </div>
        @endif
    </div>
</div>
