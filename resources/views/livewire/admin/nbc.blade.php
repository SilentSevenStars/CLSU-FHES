<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section with Enhanced Styling -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            NBC Evaluation
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Search and view NBC evaluation data
                        </p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.2s;">
                <div class="p-6">

                    <!-- Flash Messages -->
                    @if(session()->has('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-4 mb-6">

                        <!-- Search Button -->
                        <button wire:click="openSearchModal"
                            class="px-6 py-2 bg-[#0a6025] text-white rounded-lg hover:bg-green-700 font-medium flex items-center gap-2 shadow-lg hover:shadow-xl transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search Applicant
                        </button>

                        <!-- Clear Search Button -->
                        @if(!empty($searchTerm) || !empty($selectedPosition))
                        <button wire:click="clearSearch"
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-medium flex items-center gap-2 shadow-lg hover:shadow-xl transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear Search
                        </button>
                        @endif

                        <!-- Export Button -->
                        <button wire:click="export"
                            class="ml-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center gap-2 shadow-lg hover:shadow-xl transition-all duration-300"
                            @if(empty($nbcData)) disabled @endif>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export PDF
                        </button>
                    </div>

                    <!-- Active Search Display -->
                    @if(!empty($searchTerm) || !empty($selectedPosition))
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm">
                        <div class="flex items-center gap-2 text-sm text-emerald-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Active Search:</span>
                            @if(!empty($searchTerm))
                            <span class="px-3 py-1 bg-emerald-100 rounded-full text-xs font-semibold">Name: {{ $searchTerm }}</span>
                            @endif
                            @if(!empty($selectedPosition))
                            <span class="px-3 py-1 bg-emerald-100 rounded-full text-xs font-semibold">
                                Position: {{ collect($positions)->firstWhere('id', $selectedPosition)['name'] ?? 'Selected' }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Display Data -->
                    @if(!empty($searchTerm) && !empty($selectedPosition))

                    @if(count($nbcData) > 0)
                    <!-- Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#0a6025]">
                                <tr>
                                    <th rowspan="2"
                                        class="px-6 py-3 text-left text-xs font-semibold text-white uppercase border-r border-white/20">
                                        Major Components
                                    </th>
                                    <th rowspan="2"
                                        class="px-6 py-3 text-center text-xs font-semibold text-white uppercase border-r border-white/20">
                                        Maximum Points
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-white uppercase border-r border-white/20">
                                        Previous Points<br>as of
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-white uppercase border-r border-white/20">
                                        Additional Points<br>as of
                                    </th>
                                    <th rowspan="2"
                                        class="px-6 py-3 text-center text-xs font-semibold text-white uppercase border-r border-white/20">
                                        EP Subtotal
                                    </th>
                                    <th rowspan="2" class="px-6 py-3 text-center text-xs font-semibold text-white uppercase">
                                        Total Points
                                    </th>
                                </tr>
                </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($nbcData as $data)
                                <!-- Educational Qualification -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium border-r border-gray-200">
                                        1.0 Educational Qualification
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        90
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        {{ $data['previous_education'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        {{ $data['additional_education'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-semibold border-r border-gray-200 bg-emerald-50">
                                        {{ $data['ep_education_subtotal'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-bold">
                                        {{ $data['total_education'] }}
                                    </td>
                                </tr>

                                <!-- Experience and Length of Service -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium border-r border-gray-200">
                                        2.0 Experience and Length of Service
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        25
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        {{ $data['previous_experience'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        {{ $data['additional_experience'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-semibold border-r border-gray-200 bg-emerald-50">
                                        {{ $data['ep_experience_subtotal'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-bold">
                                        {{ $data['total_experience'] }}
                                    </td>
                                </tr>

                                <!-- Professional Development -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium border-r border-gray-200">
                                        3.0 Professional Development, Achievement and Honors
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        90
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        {{ $data['previous_professional'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        {{ $data['additional_professional'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-semibold border-r border-gray-200 bg-emerald-50">
                                        {{ $data['ep_professional_subtotal'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-bold">
                                        {{ $data['total_professional'] }}
                                    </td>
                                </tr>

                                <!-- Total Row -->
                                <tr class="bg-gray-100 font-bold">
                                    <td class="px-6 py-3 text-sm border-r border-gray-200">
                                        TOTAL
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        205
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        {{ $data['previous_total'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200">
                                        {{ $data['additional_total'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center border-r border-gray-200 bg-emerald-100">
                                        {{ $data['ep_total_subtotal'] }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center">
                                        {{ $data['grand_total'] }}
                                    </td>
                                </tr>

                                <!-- Projected Points Row -->
                                <tr class="bg-emerald-50">
                                    <td colspan="5" class="px-6 py-3 text-sm text-right font-semibold border-r border-gray-200">
                                        Projected Points:
                                    </td>
                                    <td class="px-6 py-3 text-sm text-center font-bold text-[#0a6025]">
                                        {{ $data['projected_points'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Applicant Information -->
                    <div class="mt-4 p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-lg border border-emerald-200 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-semibold text-gray-900">Applicant:</span>
                                <span>{{ $nbcData[0]['name'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-semibold text-gray-900">Position:</span>
                                <span>{{ $nbcData[0]['position'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="font-semibold text-gray-900">College:</span>
                                <span>{{ $nbcData[0]['college'] }}</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-lg font-medium">No evaluation data found</p>
                        <p class="text-sm mt-1">The applicant may not have been evaluated yet for this position</p>
                    </div>
                    @endif

                    @else
                    <div class="py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <p class="text-lg font-medium">Click "Search Applicant" to begin</p>
                        <p class="text-sm mt-1">Enter applicant name and select position to view evaluation data</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Search Modal -->
    @if($showSearchModal)
    <div x-data="{ show: @entangle('showSearchModal') }" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="search-modal-title" role="dialog" aria-modal="true">

        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div @click="$wire.closeSearchModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <!-- Modal Header -->
                <div class="bg-[#0a6025] px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white" id="search-modal-title">
                            Search NBC Evaluation
                        </h3>
                        <button wire:click="closeSearchModal"
                            class="text-white hover:text-gray-200 transition duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="performSearch">
                    <div class="px-6 py-5 space-y-5">

                        <!-- Flash Messages in Modal -->
                        @if(session()->has('error'))
                        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                        @endif

                        <!-- Applicant Name Search -->
                        <div>
                            <label for="tempSearchTerm" class="block text-sm font-medium text-gray-700 mb-2">
                                Applicant Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="tempSearchTerm" id="tempSearchTerm"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent"
                                    placeholder="Type applicant name..." autocomplete="off" required />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>

                                <!-- Dropdown Results -->
                                @if($showDropdown && count($searchResults) > 0 && !$selectedApplicantId)
                                <div
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                                    @foreach($searchResults as $result)
                                    <div wire:click="selectApplicant({{ $result['id'] }}, '{{ $result['full_name'] }}')"
                                        class="px-4 py-2 hover:bg-green-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition duration-150">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="text-sm text-gray-700">{{ $result['full_name'] }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                @if(strlen($tempSearchTerm) >= 2 && count($searchResults) == 0 && !$showDropdown &&
                                !$selectedApplicantId)
                                <div
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg p-4">
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>No applicants found</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Type at least 2 characters to search</p>
                        </div>

                        <!-- Position Selection -->
                        <div>
                            <label for="tempSelectedPosition" class="block text-sm font-medium text-gray-700 mb-2">
                                Position Applied For <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="tempSelectedPosition" id="tempSelectedPosition"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a6025] focus:border-transparent"
                                @if(empty($positions)) disabled @endif required>
                                <option value="">-- Select Position --</option>
                                @foreach($positions as $position)
                                <option value="{{ $position['id'] }}">
                                    {{ $position['name'] }}
                                </option>
                                @endforeach
                            </select>
                            @if(empty($positions) && !empty($tempSearchTerm) && $selectedApplicantId)
                            <p class="mt-2 text-sm text-amber-600">
                                No approved positions found for this applicant
                            </p>
                            @endif
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" wire:click="closeSearchModal"
                            class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition duration-200">
                            Cancel
                        </button>
                        <button type="submit" x-data="{
                                name: @entangle('tempSearchTerm'),
                                position: @entangle('tempSelectedPosition')
                            }" :class="(name && position)
                                ? 'bg-[#0a6025] hover:bg-green-700 cursor-pointer'
                                : 'bg-gray-400 cursor-not-allowed'" :disabled="!(name && position)"
                            class="w-full sm:w-auto px-6 py-2 text-white font-semibold rounded-lg transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @endif

</div>