@php
    $fontScales = [
        'sm'   => ['base' => 'text-sm',  'label' => 'text-xs',   'title' => 'text-base', 'heading' => 'text-2xl', 'sub' => 'text-xs',   'btn' => 'text-xs',   'th' => 'text-xs',  'td' => 'text-xs'],
        'base' => ['base' => 'text-base','label' => 'text-sm',   'title' => 'text-lg',   'heading' => 'text-3xl', 'sub' => 'text-sm',   'btn' => 'text-sm',   'th' => 'text-xs',  'td' => 'text-sm'],
        'lg'   => ['base' => 'text-lg',  'label' => 'text-base', 'title' => 'text-xl',   'heading' => 'text-4xl', 'sub' => 'text-base', 'btn' => 'text-base', 'th' => 'text-sm',  'td' => 'text-base'],
        'xl'   => ['base' => 'text-xl',  'label' => 'text-lg',   'title' => 'text-2xl',  'heading' => 'text-5xl', 'sub' => 'text-lg',   'btn' => 'text-lg',   'th' => 'text-base','td' => 'text-lg'],
    ];
    $fs = $fontScales[$fontSize ?? 'base'];
@endphp

<div x-data="{ showModal: @entangle('showModal') }">
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg shadow animate-fadeIn {{ $fs['base'] }}">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-6 animate-fadeIn">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="font-extrabold text-[#0A6025] mb-2 {{ $fs['heading'] }}">Available Positions</h1>
                        <p class="text-gray-600 flex items-center gap-2 {{ $fs['base'] }}">
                            <svg class="w-5 h-5 text-[#0A6025] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Browse and apply for available job positions
                        </p>
                    </div>

                    {{-- Right controls: Font Size + View Toggle --}}
                    <div class="flex items-center gap-2 self-start flex-wrap">

                        {{-- Font Size Selector --}}
                        <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-lg p-1 shadow-sm">
                            @foreach(['sm' => 'Small', 'base' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large'] as $size => $label)
                                <button wire:click="setFontSize('{{ $size }}')"
                                    title="{{ $label }} text"
                                    class="rounded-md px-3 py-1.5 text-xs font-medium transition-all duration-200
                                        {{ $fontSize === $size
                                            ? 'bg-[#0A6025] text-white shadow'
                                            : 'text-gray-500 hover:text-[#0A6025] hover:bg-gray-100' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                        {{-- View Toggle --}}
                        <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-lg p-1 shadow-sm">
                            <button wire:click="setViewType('card')" title="Card View"
                                class="p-2 rounded-md transition-all duration-200 {{ $viewType === 'card' ? 'bg-[#0A6025] text-white shadow' : 'text-gray-400 hover:text-[#0A6025] hover:bg-gray-100' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button wire:click="setViewType('table')" title="Table View"
                                class="p-2 rounded-md transition-all duration-200 {{ $viewType === 'table' ? 'bg-[#0A6025] text-white shadow' : 'text-gray-400 hover:text-[#0A6025] hover:bg-gray-100' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M3 14h18M10 4v16M3 6a1 1 0 011-1h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Active Application Banner --}}
            @if($hasActiveApplication)
                <div class="mb-6 p-4 bg-amber-50 border border-amber-300 text-amber-800 rounded-lg shadow flex items-start gap-3 animate-fadeIn">
                    <svg class="w-6 h-6 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold {{ $fs['base'] }}">You already have an active application.</p>
                        <p class="{{ $fs['label'] }} mt-1">You can only apply to one position at a time. Other positions will be available once your current application is archived or removed.</p>
                    </div>
                </div>
            @endif

            {{-- Search + Per Page Row --}}
            <div class="mb-6 flex flex-wrap items-center gap-3 animate-fadeIn">
                <div class="relative flex-1 min-w-[220px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] shadow-sm {{ $fs['base'] }}"
                        placeholder="Search by position, department, college, or specialization..." />
                </div>

                @if($viewType === 'table')
                    <div class="flex items-center gap-2 {{ $fs['base'] }} text-gray-600">
                        <span>Show</span>
                        <select wire:model.live="perPage"
                            class="border border-gray-300 rounded-lg px-2 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#0A6025] shadow-sm {{ $fs['base'] }}">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entries</span>
                    </div>
                @endif
            </div>

            {{-- Empty State --}}
            @if(!$positions || $positions->isEmpty())
                <div class="bg-white rounded-xl shadow-xl p-12 text-center animate-fadeIn">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full p-6 w-24 h-24 mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2 {{ $fs['title'] }}">No Available Positions</h3>
                        <p class="text-gray-500 {{ $fs['base'] }}">
                            @if(!empty($search))
                                No positions match your search criteria. Try different keywords.
                            @else
                                There are no job positions available at the moment. Please check back later.
                            @endif
                        </p>
                    </div>
                </div>

            @elseif($viewType === 'card')
                {{-- ======================== CARD VIEW ======================== --}}
                <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-8">
                    @foreach($positions as $index => $position)
                        @php
                            $isAppliedToThis = in_array($position->id, $applied);
                            $isLockedOut = $hasActiveApplication && !$isAppliedToThis;
                        @endphp
                        <div class="group bg-white rounded-xl shadow-lg transition-all duration-300 animate-slideInLeft border-l-4 hover:shadow-2xl transform hover:-translate-y-1 border-[#0A6025]"
                            style="animation-delay: {{ $index * 0.05 }}s;">
                            <div class="p-6">
                                <div class="rounded-2xl p-4 shadow-lg w-16 h-16 flex items-center justify-center mb-4 bg-gradient-to-br from-yellow-500 to-[#0A6025] group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <h5 class="font-bold mb-3 leading-tight transition-colors duration-300 text-gray-800 group-hover:text-[#0A6025] {{ $fs['title'] }}">
                                    {{ $position->name }}
                                    @if($position->department && $position->department->name)
                                        - {{ $position->department->name }}
                                    @endif
                                </h5>

                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="font-medium text-gray-600 {{ $fs['base'] }}">
                                        {{ $position->college->name ?? 'Various Colleges' }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 {{ $fs['label'] }}">
                                        {{ \Carbon\Carbon::parse($position->start_date)->format('M d, Y') }} –
                                        {{ \Carbon\Carbon::parse($position->end_date)->format('M d, Y') }}
                                    </p>
                                </div>

                                @if($isAppliedToThis)
                                    <button wire:click="viewDetails({{ $position->id }})"
                                        class="block w-full text-center font-semibold rounded-lg px-4 py-3 transition-all duration-300 text-white bg-yellow-600 hover:bg-yellow-700 shadow-md hover:shadow-lg {{ $fs['btn'] }}">
                                        View Application
                                    </button>
                                @elseif($isLockedOut)
                                    <button disabled
                                        class="block w-full text-center font-semibold rounded-lg px-4 py-3 bg-gray-200 text-gray-400 cursor-not-allowed select-none {{ $fs['btn'] }}">
                                        Not Available
                                    </button>
                                @else
                                    <button wire:click="viewDetails({{ $position->id }})"
                                        class="block w-full text-center font-semibold rounded-lg px-4 py-3 transition-all duration-300 text-white bg-[#0A6025] hover:bg-[#0B712C] focus:ring-4 focus:ring-[#0A6025] shadow-md hover:shadow-lg transform hover:scale-105 {{ $fs['btn'] }}">
                                        View Details
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                {{-- ======================== TABLE VIEW ======================== --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-4 animate-fadeIn">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-[#0A6025] text-white uppercase tracking-wide {{ $fs['th'] }}">
                                    <th class="px-4 py-3 font-semibold w-44">
                                        College / Unit
                                        <span class="block text-green-200 normal-case font-normal mt-0.5 {{ $fs['th'] }}">Department / Office</span>
                                    </th>
                                    <th class="px-4 py-3 font-semibold">Position</th>
                                    <th class="px-4 py-3 font-semibold">Specialization</th>
                                    <th class="px-4 py-3 font-semibold">Education</th>
                                    <th class="px-4 py-3 font-semibold">Experience</th>
                                    <th class="px-4 py-3 font-semibold">Relevant Training</th>
                                    <th class="px-4 py-3 font-semibold">Eligibility</th>
                                    <th class="px-4 py-3 font-semibold">Date</th>
                                    <th class="px-4 py-3 font-semibold text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($paginatedPositions ?? collect() as $position)
                                    @php
                                        $isAppliedToThis = in_array($position->id, $applied);
                                        $isLockedOut     = $hasActiveApplication && !$isAppliedToThis;
                                        $expWords        = $this->numberToWords((int)($position->experience ?? 0));
                                        $trainWords      = $this->numberToWords((int)($position->training ?? 0));
                                        $expSuffix       = (int)($position->experience ?? 0) === 1 ? '' : 's';
                                        $trainSuffix     = (int)($position->training ?? 0) === 1 ? '' : 's';
                                    @endphp
                                    <tr class="transition-colors duration-150 {{ $fs['td'] }}
                                        {{ $isAppliedToThis
                                            ? 'hover:bg-yellow-50 border-l-4 border-l-yellow-400'
                                            : ($isLockedOut
                                                ? 'hover:bg-gray-50 opacity-60 border-l-4 border-l-gray-300'
                                                : 'hover:bg-green-50 border-l-4 border-l-[#0A6025]') }}">

                                        <td class="px-4 py-3 w-44">
                                            <span class="block font-semibold text-gray-800 leading-tight">
                                                {{ $position->college->name ?? 'Various Colleges' }}
                                            </span>
                                            @if($position->department && $position->department->name)
                                                <span class="block text-gray-500 mt-0.5 leading-tight {{ $fs['label'] }}">
                                                    {{ $position->department->name }}
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 font-semibold text-gray-800">
                                            {{ $position->name }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $position->specialization ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $position->education ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $expWords }} year{{ $expSuffix }} of teaching and relevant experience
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $trainWords }} hour{{ $trainSuffix }} of relevant training
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $position->eligibility ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap {{ $fs['label'] }}">
                                            {{ \Carbon\Carbon::parse($position->start_date)->format('M d, Y') }}<br>
                                            <span class="text-gray-400">to</span><br>
                                            {{ \Carbon\Carbon::parse($position->end_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            @if($isAppliedToThis)
                                                <button wire:click="viewDetails({{ $position->id }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 font-semibold rounded-lg text-white bg-yellow-600 hover:bg-yellow-700 transition-colors duration-200 shadow {{ $fs['btn'] }}">
                                                    View Application
                                                </button>
                                            @elseif($isLockedOut)
                                                <span class="inline-block px-3 py-1.5 font-medium rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed {{ $fs['btn'] }}">
                                                    Not Available
                                                </span>
                                            @else
                                                <button wire:click="viewDetails({{ $position->id }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 font-semibold rounded-lg text-white bg-[#0A6025] hover:bg-[#0B712C] transition-colors duration-200 shadow {{ $fs['btn'] }}">
                                                    View Details
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-12 text-center text-gray-400 {{ $fs['base'] }}">
                                            No positions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if($totalCount > 0)
                    <div class="flex flex-wrap items-center justify-between gap-4 text-gray-600 mb-8 {{ $fs['base'] }}">
                        <span>
                            Showing
                            {{ ($currentPage - 1) * $perPage + 1 }}–{{ min($currentPage * $perPage, $totalCount) }}
                            of {{ $totalCount }} entries
                        </span>
                        <div class="flex items-center gap-1">
                            <button wire:click="prevPage" @disabled($currentPage <= 1)
                                class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors duration-150 font-medium {{ $fs['btn'] }}">
                                ← Prev
                            </button>

                            @for($p = 1; $p <= $totalPages; $p++)
                                @if($p === 1 || $p === $totalPages || abs($p - $currentPage) <= 1)
                                    <button wire:click="goToPage({{ $p }})"
                                        class="px-3 py-1.5 rounded-lg border font-medium transition-colors duration-150 {{ $fs['btn'] }}
                                            {{ $p === $currentPage
                                                ? 'bg-[#0A6025] text-white border-[#0A6025]'
                                                : 'border-gray-300 bg-white hover:bg-gray-50 text-gray-700' }}">
                                        {{ $p }}
                                    </button>
                                @elseif(abs($p - $currentPage) === 2)
                                    <span class="px-2 text-gray-400">…</span>
                                @endif
                            @endfor

                            <button wire:click="nextPage" @disabled($currentPage >= $totalPages)
                                class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors duration-150 font-medium {{ $fs['btn'] }}">
                                Next →
                            </button>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>

    {{-- ======================== MODAL ======================== --}}
    <div x-show="showModal" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="$wire.closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">

                @if($selectedPosition)
                    @php
                        $selectedIsApplied = in_array($selectedPosition->id, $applied);
                        $modalExpWords     = $this->numberToWords((int)($selectedPosition->experience ?? 0));
                        $modalTrainWords   = $this->numberToWords((int)($selectedPosition->training ?? 0));
                        $expSuffix         = (int)($selectedPosition->experience ?? 0) === 1 ? '' : 's';
                        $trainSuffix       = (int)($selectedPosition->training ?? 0) === 1 ? '' : 's';
                    @endphp

                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-[#0A6025] to-[#0B712C] px-6 py-4 sticky top-0 z-10">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-white {{ $fs['title'] }}" id="modal-title">
                                {{ $selectedPosition->name }}
                            </h3>
                            <button @click="$wire.closeModal()" class="text-white hover:text-gray-200 transition-colors duration-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                        <div class="space-y-6">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-gray-500 mb-1 {{ $fs['label'] }}">College / Unit</h4>
                                    <p class="font-medium text-gray-800 {{ $fs['base'] }}">
                                        {{ $selectedPosition->college->name ?? 'Various Colleges' }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-gray-500 mb-1 {{ $fs['label'] }}">Department / Office</h4>
                                    <p class="font-medium text-gray-800 {{ $fs['base'] }}">
                                        {{ ($selectedPosition->department && $selectedPosition->department->name) ? $selectedPosition->department->name : '—' }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-gray-500 mb-1 {{ $fs['label'] }}">Start of Application</h4>
                                    <p class="font-medium text-gray-800 {{ $fs['base'] }}">
                                        {{ \Carbon\Carbon::parse($selectedPosition->start_date)->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-gray-500 mb-1 {{ $fs['label'] }}">End of Application</h4>
                                    <p class="font-medium text-gray-800 {{ $fs['base'] }}">
                                        {{ \Carbon\Carbon::parse($selectedPosition->end_date)->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-500 mb-2 {{ $fs['label'] }}">Specialization</h4>
                                <p class="text-gray-800 {{ $fs['base'] }}">{{ $selectedPosition->specialization ?? '—' }}</p>
                            </div>

                            <div class="border-t pt-6">
                                <h4 class="font-bold text-gray-800 mb-4 {{ $fs['title'] }}">Requirements</h4>
                                <div class="space-y-4">

                                    <div class="flex items-start gap-3">
                                        <svg class="w-6 h-6 text-[#0A6025] mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <div>
                                            <h5 class="font-semibold text-gray-800 {{ $fs['base'] }}">Education</h5>
                                            <p class="text-gray-600 {{ $fs['base'] }}">{{ $selectedPosition->education ?? '—' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <svg class="w-6 h-6 text-[#0A6025] mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <h5 class="font-semibold text-gray-800 {{ $fs['base'] }}">Experience</h5>
                                            <p class="text-gray-600 {{ $fs['base'] }}">
                                                {{ $modalExpWords }} year{{ $expSuffix }} of teaching and relevant experience
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <svg class="w-6 h-6 text-[#0A6025] mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <h5 class="font-semibold text-gray-800 {{ $fs['base'] }}">Relevant Training</h5>
                                            <p class="text-gray-600 {{ $fs['base'] }}">
                                                {{ $modalTrainWords }} hour{{ $trainSuffix }} of relevant training
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <svg class="w-6 h-6 text-[#0A6025] mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div>
                                            <h5 class="font-semibold text-gray-800 {{ $fs['base'] }}">Eligibility</h5>
                                            <p class="text-gray-600 {{ $fs['base'] }}">{{ $selectedPosition->eligibility ?? '—' }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 sticky bottom-0 border-t border-gray-200">
                        <div class="flex items-center justify-end gap-3">
                            <button @click="$wire.closeModal()"
                                class="px-6 py-2.5 font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 {{ $fs['btn'] }}">
                                Close
                            </button>

                            @if($selectedIsApplied)
                                @if($this->canEditApplication($selectedPosition->id))
                                    <a href="{{ route('edit-job-application', ['application_id' => $this->getApplicationId($selectedPosition->id)]) }}"
                                        class="px-6 py-2.5 font-semibold text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 transition-all duration-200 shadow-md hover:shadow-lg {{ $fs['btn'] }}">
                                        Edit Application
                                    </a>
                                @else
                                    <button disabled
                                        class="px-6 py-2.5 font-semibold text-gray-500 bg-gray-300 rounded-lg cursor-not-allowed {{ $fs['btn'] }}">
                                        Application Submitted
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('job-application', ['position_id' => $selectedPosition->id]) }}"
                                    class="px-6 py-2.5 font-semibold text-white bg-[#0A6025] rounded-lg hover:bg-[#0B712C] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A6025] transition-all duration-200 shadow-md hover:shadow-lg {{ $fs['btn'] }}">
                                    Apply Now
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
        .animate-slideInLeft { animation: slideInLeft 0.5s ease-out; }
    </style>
</div>