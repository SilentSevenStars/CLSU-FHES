<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">NBC Evaluation</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">

        <!-- Flash Messages -->
        @if(session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="flex items-center gap-4 mb-6">

            <!-- Search by Name -->
            <div class="flex-1 relative">
                <input 
                    type="text"
                    wire:model.live.debounce.300ms="searchTerm"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="Search applicant name"
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
                    @if(empty($positions)) disabled @endif
                >
                    <option value="">Select Position</option>
                    @foreach($positions as $position)
                        <option value="{{ $position['id'] }}">
                            {{ $position['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Export -->
            <button 
                wire:click="export"
                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium disabled:bg-gray-400 disabled:cursor-not-allowed"
                @if(empty($nbcData)) disabled @endif
            >
                Export
            </button>
        </div>

        <!-- Display Data Only When Both Name and Position Are Selected -->
        @if(!empty($searchTerm) && !empty($selectedPosition))
            
            @if(count($nbcData) > 0)
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase border-r border-gray-300">
                                    Major Components
                                </th>
                                <th rowspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase border-r border-gray-300">
                                    Maximum Points
                                </th>
                                <th colspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase border-r border-gray-300">
                                    Previous Points<br>as of
                                </th>
                                <th colspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase border-r border-gray-300">
                                    Additional Points<br>as of
                                </th>
                                <th colspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase">
                                    Total
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($nbcData as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium border-r border-gray-300">
                                        1.0 Educational Qualification
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300">
                                        85
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300" colspan="2">
                                        {{ $data['previous_education'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300" colspan="2">
                                        {{ $data['additional_education'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-semibold" colspan="2">
                                        {{ $data['total_education'] }}
                                    </td>
                                </tr>

                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium border-r border-gray-300">
                                        2.0 Experience and Length of Service
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300">
                                        25
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300" colspan="2">
                                        {{ $data['previous_experience'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300" colspan="2">
                                        {{ $data['additional_experience'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-semibold" colspan="2">
                                        {{ $data['total_experience'] }}
                                    </td>
                                </tr>

                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium border-r border-gray-300">
                                        3.0 Professional Development, Achievement and Honors
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300">
                                        90
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300" colspan="2">
                                        {{ $data['previous_professional'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300" colspan="2">
                                        {{ $data['additional_professional'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-semibold" colspan="2">
                                        {{ $data['total_professional'] }}
                                    </td>
                                </tr>

                                <tr class="bg-gray-100 font-bold">
                                    <td class="px-6 py-3 text-sm border-r border-gray-300">
                                        TOTAL
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300">
                                        200
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300" colspan="2">
                                        {{ $data['previous_total'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-300" colspan="2">
                                        {{ $data['additional_total'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center" colspan="2">
                                        {{ $data['grand_total'] }}
                                    </td>
                                </tr>

                                <tr class="bg-green-50">
                                    <td colspan="6" class="px-6 py-3 text-sm text-right font-semibold border-r border-gray-300">
                                        Projected Points:
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-bold text-green-700" colspan="2">
                                        {{ $data['projected_points'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-sm text-gray-600">
                    <div>Applicant: <span class="font-semibold">{{ $nbcData[0]['name'] }}</span></div>
                    <div>Position: <span class="font-semibold">{{ $nbcData[0]['position'] }}</span></div>
                    <div>College: <span class="font-semibold">{{ $nbcData[0]['college'] }}</span></div>
                </div>
            @else
                <div class="py-12 text-center text-gray-500">
                    <p class="text-lg font-medium">No applicant found</p>
                    <p class="text-sm mt-1">Please check the name and position filter</p>
                </div>
            @endif

        @else
            <div class="py-12 text-center text-gray-500">
                <p class="text-lg font-medium">Please enter applicant name and select position</p>
                <p class="text-sm mt-1">Data will appear when both fields are filled</p>
            </div>
        @endif
    </div>
</div>