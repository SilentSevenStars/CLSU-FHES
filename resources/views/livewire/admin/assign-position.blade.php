<div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="mb-8 animate-fadeIn">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-2">
                        Assign Position
                    </h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1E7F3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        Promote or hire applicants to their evaluated positions
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert Modal -->
        <div x-data="{ show: @entangle('showAlertModal') }" x-show="show" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="$wire.closeAlertModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                :class="{ 'bg-green-100': '{{ $alertType }}' === 'success', 'bg-red-100': '{{ $alertType }}' === 'error' }">
                                <svg x-show="'{{ $alertType }}' === 'success'" class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <svg x-show="'{{ $alertType }}' === 'error'" class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    <span x-show="'{{ $alertType }}' === 'success'" class="text-green-600">Success!</span>
                                    <span x-show="'{{ $alertType }}' === 'error'" class="text-red-600">Error!</span>
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">{{ $alertMessage }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="closeAlertModal"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200"
                            :class="{ 'bg-green-600 hover:bg-green-700 focus:ring-green-500': '{{ $alertType }}' === 'success', 'bg-red-600 hover:bg-red-700 focus:ring-red-500': '{{ $alertType }}' === 'error' }">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
            <!-- Table Header -->
            <div class="bg-[#1E7F3E] p-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <i class="fa-solid fa-user-check text-white text-lg"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">
                            {{ $showArchived ? 'Archived Applications' : 'Applicants for Assignment' }}
                        </h2>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button wire:click="$toggle('showArchived')"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200
                                {{ $showArchived ? 'bg-white text-[#1E7F3E]' : 'bg-white/20 text-white hover:bg-white/30' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            {{ $showArchived ? 'View Active' : 'View Archived' }}
                        </button>
                        <select wire:model.live="perPage"
                            class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                            <option value="5">5 / page</option>
                            <option value="10">10 / page</option>
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                            <option value="100">100 / page</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                <!-- Filters / Search Row -->
                                <div class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-300 gap-3">
                                    <div class="flex items-center gap-4 flex-wrap">
                                        <button wire:click="openSearchModal"
                                            class="inline-flex items-center px-4 py-2 bg-[#156B2D] hover:bg-[#125A26] text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            Search Applicant
                                        </button>

                                        @if($search || $positionFilter)
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if($search)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                                Name: {{ $search }}
                                                <button wire:click="$set('search', '')" class="ml-2 focus:outline-none">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </span>
                                            @endif
                                            @if($positionFilter)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                Position: {{ $positionFilter }}
                                                <button wire:click="$set('positionFilter', '')" class="ml-2 focus:outline-none">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Table -->
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start"><span class="text-xs font-semibold uppercase text-black">Applicant Name</span></th>
                                            <th scope="col" class="px-6 py-3 text-start"><span class="text-xs font-semibold uppercase text-black">Current Position</span></th>
                                            <th scope="col" class="px-6 py-3 text-start"><span class="text-xs font-semibold uppercase text-black">Applied Position</span></th>
                                            <th scope="col" class="px-6 py-3 text-start"><span class="text-xs font-semibold uppercase text-black">Evaluation Status</span></th>
                                            <th scope="col" class="px-6 py-3 text-start"><span class="text-xs font-semibold uppercase text-black">Actions</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-300 bg-gray-50">
                                        @forelse($applicants as $applicant)
                                            @foreach($applicant->jobApplications as $application)
                                                @if($application->evaluation && ($application->archive == $showArchived) && $application->status !== 'hired')
                                                <tr class="bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $applicant->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $applicant->user->email }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            {{ $applicant->position ?? 'None' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                            {{ $application->position->name }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php
                                                            $hasPanelComplete = $application->evaluation->panelAssignments()->where('status', 'complete')->exists();
                                                            $hasNbcComplete   = $application->evaluation->nbcAssignments()->where('status', 'complete')->exists();
                                                            $isComplete       = $hasPanelComplete || $hasNbcComplete;
                                                        @endphp
                                                        @if($isComplete)
                                                            <span class="px-3 py-1.5 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓ Complete</span>
                                                        @else
                                                            <span class="px-3 py-1.5 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-600">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <div class="flex items-center gap-2">
                                                            @if(!$showArchived)
                                                            <button wire:click="openConfirmModal({{ $applicant->id }}, {{ $application->evaluation->id }})"
                                                                class="bg-[#1E7F3E] hover:bg-[#156B2D] text-white px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2">
                                                                {{ $applicant->hired ? 'Promote' : 'Assign Position' }}
                                                            </button>
                                                            <button wire:click="openArchiveModal({{ $application->id }})"
                                                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                                                                title="Archive this application">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                                </svg>
                                                            </button>
                                                            @else
                                                            <button wire:click="unarchive({{ $application->id }})"
                                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2">
                                                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                                                </svg>
                                                                Unarchive
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <p class="text-lg font-medium">No applicants found</p>
                                                    <p class="text-sm">Try adjusting your search or filter criteria</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- Pagination -->
                                <div class="p-4 bg-white border-t border-gray-300">
                                    {{ $applicants->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================
             Search Modal
        ====================================================== -->
        <div x-data="{ show: @entangle('showSearchModal') }" x-show="show" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="$wire.closeSearchModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Search &amp; Filter Applicants</h3>
                            <button wire:click="closeSearchModal" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div class="relative" x-data="{ open: @entangle('showDropdown') }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Search by Name</label>
                                <input type="text" wire:model.live.debounce.300ms="searchInput"
                                    placeholder="Type applicant name..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent"
                                    autocomplete="off">
                                <div x-show="open" x-cloak
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                                    @if(!empty($filteredNames))
                                        @foreach($filteredNames as $name)
                                        <div wire:click="selectName('{{ $name }}')"
                                            class="px-4 py-2 hover:bg-emerald-50 cursor-pointer transition-colors duration-150">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-900">{{ $name }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">No matching names found</div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Applied Position</label>
                                <select wire:model="tempPositionFilter"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent">
                                    <option value="">All Positions</option>
                                    @foreach($availablePositions as $position)
                                    <option value="{{ $position }}">{{ $position }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="button" wire:click="applySearch"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#1E7F3E] text-base font-medium text-white hover:bg-[#156B2D] focus:outline-none sm:w-auto sm:text-sm">Apply</button>
                        <button type="button" wire:click="clearFilters"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">Clear All</button>
                        <button type="button" wire:click="closeSearchModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================
             Confirm Assignment Modal (Hire / Promote)
        ====================================================== -->
        <div x-data="{ show: @entangle('showConfirmModal') }" x-show="show" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Confirm Position Assignment</h3>

                                @if($selectedApplicant && $selectedEvaluation)
                                <div class="mt-2 space-y-4">
                                    <p class="text-sm text-gray-500">Are you sure you want to assign the following position?</p>

                                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="font-medium text-gray-700">Applicant:</span>
                                            <span class="text-gray-900">{{ $selectedApplicant->user->name }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="font-medium text-gray-700">Current Position:</span>
                                            <span class="text-gray-900">{{ $selectedApplicant->position ?? 'None' }}</span>
                                        </div>
                                    </div>

                                    <!-- ══════════════════════════════════════════
                                         Requirements Checklist
                                    ══════════════════════════════════════════ -->
                                    @php
                                        $posName = $selectedEvaluation->jobApplication->position->name ?? '';
                                        $posRanks = [
                                            'Instructor I'            => 1,
                                            'Instructor II'           => 2,
                                            'Instructor III'          => 3,
                                            'Assistant Professor I'   => 4,
                                            'Assistant Professor II'  => 5,
                                            'Assistant Professor III' => 6,
                                            'Assistant Professor IV'  => 7,
                                            'Associate Professor I'   => 8,
                                            'Associate Professor II'  => 9,
                                            'Associate Professor III' => 10,
                                            'Associate Professor IV'  => 11,
                                            'Associate Professor V'   => 12,
                                            'Professor I'             => 13,
                                            'Professor II'            => 14,
                                            'Professor III'           => 15,
                                            'Professor IV'            => 16,
                                            'Professor V'             => 17,
                                            'Professor VI'            => 18,
                                        ];
                                        $specialColleges = [
                                            'College of Engineering',
                                            'College of Business Administration and Accountancy',
                                            'College of Veterinary Science and Medicine',
                                        ];
                                        $posRank        = $posRanks[$posName] ?? null;
                                        $effectiveCollege = \App\Models\College::find($confirmCollegeId)?->name ?? '';
                                        $isSpecial      = in_array($effectiveCollege, $specialColleges);

                                        // Panel data
                                        $panelRows = $selectedEvaluation->panelAssignments()->with(['interview','experience','performance'])->get();
                                        $hasPanelInterview   = $panelRows->contains(fn($pa) => !is_null($pa->interview_id)   && !is_null($pa->interview));
                                        $hasPanelExperience  = $panelRows->contains(fn($pa) => !is_null($pa->experience_id)  && !is_null($pa->experience));
                                        $hasPanelPerformance = $panelRows->contains(fn($pa) => !is_null($pa->performance_id) && !is_null($pa->performance));
                                        $hasNbc = $selectedEvaluation->nbcAssignments()
                                            ->where('status', 'complete')
                                            ->whereNotNull('educational_qualification_id')
                                            ->whereNotNull('experience_service_id')
                                            ->whereNotNull('professional_development_id')
                                            ->exists();

                                        // Determine required items
                                        if ($posRank === null) {
                                            $requirePanelInterview = $requirePanelExperience = $requirePanelPerformance = $requireNbc = true;
                                        } elseif ($posRank <= 2) {
                                            // Instructor I & II — any college
                                            $requirePanelInterview   = true;
                                            $requirePanelExperience  = true;
                                            $requirePanelPerformance = true;
                                            $requireNbc              = false;
                                        } elseif ($posRank >= 3 && $posRank <= 4) {
                                            // Instructor III & Assistant Professor I
                                            $requirePanelInterview   = true;
                                            $requirePanelPerformance = true;
                                            // Special colleges → Panel Experience instead of NBC
                                            $requirePanelExperience  = $isSpecial;
                                            $requireNbc              = !$isSpecial;
                                        } else {
                                            // Assistant Professor II and above (all colleges)
                                            $requirePanelInterview   = true;
                                            $requirePanelPerformance = true;
                                            $requirePanelExperience  = false;
                                            $requireNbc              = true;
                                        }

                                        $requirementItems = [];
                                        if ($requirePanelInterview)   $requirementItems[] = ['label' => 'Panel: Interview',    'met' => $hasPanelInterview];
                                        if ($requirePanelExperience)  $requirementItems[] = ['label' => 'Panel: Experience',   'met' => $hasPanelExperience];
                                        if ($requirePanelPerformance) $requirementItems[] = ['label' => 'Panel: Performance',  'met' => $hasPanelPerformance];
                                        if ($requireNbc)              $requirementItems[] = ['label' => 'NBC Evaluation (complete with all sub-records)', 'met' => $hasNbc];

                                        $allRequirementsMet = collect($requirementItems)->every(fn($r) => $r['met']);
                                    @endphp

                                    <div class="border rounded-lg overflow-hidden {{ $allRequirementsMet ? 'border-green-200' : 'border-red-200' }}">
                                        {{-- Header bar --}}
                                        <div class="px-4 py-2.5 flex items-center gap-2 {{ $allRequirementsMet ? 'bg-[#1E7F3E]' : 'bg-red-600' }}">
                                            @if($allRequirementsMet)
                                                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <p class="text-white text-sm font-semibold">All Requirements Met</p>
                                            @else
                                                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                                </svg>
                                                <p class="text-white text-sm font-semibold">Requirements Incomplete — Cannot Assign</p>
                                            @endif
                                        </div>
                                        {{-- Body --}}
                                        <div class="px-4 py-3 space-y-2 {{ $allRequirementsMet ? 'bg-green-50' : 'bg-red-50' }}">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">
                                                Requirements for <span class="text-gray-800">{{ $posName }}</span>
                                                @if($effectiveCollege)
                                                    &mdash; <span class="text-gray-800">{{ $effectiveCollege }}</span>
                                                @endif
                                            </p>

                                            @foreach($requirementItems as $req)
                                            <div class="flex items-center gap-2.5">
                                                @if($req['met'])
                                                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 border border-green-300 flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </span>
                                                    <span class="text-sm text-green-800 font-medium">{{ $req['label'] }}</span>
                                                @else
                                                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-red-100 border border-red-300 flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </span>
                                                    <span class="text-sm text-red-700 font-medium">
                                                        {{ $req['label'] }}
                                                        <span class="text-red-400 font-normal text-xs ml-1">(missing or incomplete)</span>
                                                    </span>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Verification Selects -->
                                    <div class="border border-green-200 rounded-lg overflow-hidden">
                                        <div class="bg-[#1E7F3E] px-4 py-2">
                                            <p class="text-white text-sm font-semibold">Verify Assignment Details</p>
                                            <p class="text-green-100 text-xs">Pre-filled from the job application — please confirm these are correct.</p>
                                        </div>
                                        <div class="p-4 space-y-3 bg-green-50">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">New Position</label>
                                                <select wire:model="confirmPositionId"
                                                    class="w-full px-3 py-2 border border-green-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent bg-white">
                                                    @foreach(\App\Models\Position::orderBy('name')->get() as $pos)
                                                    <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">College</label>
                                                <select wire:model.live="confirmCollegeId"
                                                    class="w-full px-3 py-2 border border-green-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent bg-white">
                                                    <option value="">— No college —</option>
                                                    @foreach($colleges as $college)
                                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Department</label>
                                                <select wire:model="confirmDepartmentId"
                                                    class="w-full px-3 py-2 border border-green-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent bg-white"
                                                    {{ !$confirmCollegeId ? 'disabled' : '' }}>
                                                    <option value="">— No department —</option>
                                                    @foreach($this->departmentsForConfirm as $dept)
                                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if(!$confirmCollegeId)
                                                <p class="text-xs text-gray-400 mt-1">Select a college first to filter departments.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Message to Applicant
                                            <span class="font-normal text-gray-500">(sent via email &amp; notification)</span>
                                        </label>
                                        <textarea wire:model.defer="admin_message" rows="8"
                                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-700 focus:ring-2 focus:ring-[#1E7F3E] focus:border-transparent"
                                            placeholder="Write a message to the applicant..."></textarea>
                                        <p class="mt-1 text-xs text-gray-500">This message will be sent to the applicant's email and appear in their notification inbox.</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Attachments
                                            <span class="font-normal text-gray-500">(optional — PDF, Word, images, etc. Max 10 MB each)</span>
                                        </label>
                                        <label for="assign-file-upload"
                                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-green-300 rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition-colors duration-200">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-2 text-[#1E7F3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="text-sm text-gray-600"><span class="font-semibold text-[#1E7F3E]">Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-400 mt-1">Multiple files allowed</p>
                                            </div>
                                            <input id="assign-file-upload" type="file" class="hidden"
                                                wire:model="attachments" multiple
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls,.ppt,.pptx,.txt,.zip">
                                        </label>
                                        <div wire:loading wire:target="attachments" class="mt-2 flex items-center gap-2 text-sm text-green-700">
                                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Uploading files...
                                        </div>
                                        @error('attachments.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                        @if(!empty($attachments))
                                        <ul class="mt-3 space-y-2">
                                            @foreach($attachments as $index => $file)
                                            <li class="flex items-center justify-between bg-white border border-green-200 rounded-lg px-3 py-2">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <div class="w-8 h-8 bg-[#1E7F3E] rounded flex items-center justify-center flex-shrink-0">
                                                        <span class="text-white text-xs font-bold">{{ strtoupper(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)) }}</span>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $file->getClientOriginalName() }}</p>
                                                        <p class="text-xs text-gray-500">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                                                    </div>
                                                </div>
                                                <button type="button" wire:click="removeAttachment({{ $index }})"
                                                    class="ml-3 flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>

                                    <p class="text-xs text-gray-500">* This will mark the applicant as hired and send the notification above</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        {{-- Confirm button is disabled if requirements are not met --}}
                        <button type="button" wire:click="confirmAssignment"
                            wire:loading.attr="disabled" wire:target="confirmAssignment"
                            @if($selectedEvaluation)
                                @php
                                    // Recompute $allRequirementsMet for button state (already computed above in @php block)
                                @endphp
                                {{ !$allRequirementsMet ? 'disabled' : '' }}
                            @endif
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed
                                {{ ($selectedEvaluation && !$allRequirementsMet) ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500' }}">
                            <span wire:loading.remove wire:target="confirmAssignment">Confirm</span>
                            <span wire:loading wire:target="confirmAssignment" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                        <button type="button" wire:click="closeConfirmModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================
             Archive Confirmation Modal
        ====================================================== -->
        <div x-data="{ show: @entangle('showArchiveModal') }" x-show="show" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Archive Job Application</h3>

                                @if($selectedJobApplication)
                                <div class="mt-2 space-y-4">
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="font-medium text-gray-700">Applicant:</span>
                                            <span class="text-gray-900">{{ $selectedJobApplication->applicant->user->name }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="font-medium text-gray-700">Position:</span>
                                            <span class="text-gray-900">{{ $selectedJobApplication->position->name }}</span>
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to archive this application? You can optionally send the applicant a message and/or attachments.
                                    </p>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Message to Applicant
                                            <span class="font-normal text-gray-400">(optional)</span>
                                        </label>
                                        <textarea wire:model.defer="archive_message" rows="5"
                                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-700 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                            placeholder="Write an optional message to the applicant..."></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Attachments
                                            <span class="font-normal text-gray-400">(optional — Max 10 MB each)</span>
                                        </label>
                                        <label for="archive-file-upload"
                                            class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-yellow-300 rounded-lg cursor-pointer bg-yellow-50 hover:bg-yellow-100 transition-colors duration-200">
                                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                                <svg class="w-7 h-7 mb-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="text-sm text-gray-600"><span class="font-semibold text-yellow-700">Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-400 mt-1">Multiple files allowed</p>
                                            </div>
                                            <input id="archive-file-upload" type="file" class="hidden"
                                                wire:model="archiveAttachments" multiple
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls,.ppt,.pptx,.txt,.zip">
                                        </label>
                                        <div wire:loading wire:target="archiveAttachments" class="mt-2 flex items-center gap-2 text-sm text-yellow-700">
                                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Uploading files...
                                        </div>
                                        @error('archiveAttachments.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                        @if(!empty($archiveAttachments))
                                        <ul class="mt-3 space-y-2">
                                            @foreach($archiveAttachments as $index => $file)
                                            <li class="flex items-center justify-between bg-white border border-yellow-200 rounded-lg px-3 py-2">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <div class="w-8 h-8 bg-yellow-600 rounded flex items-center justify-center flex-shrink-0">
                                                        <span class="text-white text-xs font-bold">{{ strtoupper(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)) }}</span>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $file->getClientOriginalName() }}</p>
                                                        <p class="text-xs text-gray-500">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                                                    </div>
                                                </div>
                                                <button type="button" wire:click="removeArchiveAttachment({{ $index }})"
                                                    class="ml-3 flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>

                                    <p class="text-xs text-gray-400">* Archived applications will not be visible in the active list. Email will only be sent if a message or attachment is provided.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="confirmArchive" wire:loading.attr="disabled" wire:target="confirmArchive"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="confirmArchive">Archive</span>
                            <span wire:loading wire:target="confirmArchive" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Archiving...
                            </span>
                        </button>
                        <button type="button" wire:click="closeArchiveModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>