<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            {{-- ── Header ──────────────────────────────────────────────────────── --}}
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-2">
                            Account Activities
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-[#1E7F3E]"></i>
                            Real-time record of all user actions across the system
                        </p>
                    </div>

                    {{-- Live indicator --}}
                    <div class="flex items-center gap-2 bg-white border border-green-200 rounded-full px-4 py-2 shadow-sm">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="text-sm font-medium text-green-700">Live Updates</span>
                    </div>
                </div>
            </div>

            {{-- ── Stat Cards ───────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fadeIn">

                {{-- Total Activities --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Total Activities</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalActivities }}</h3>
                        </div>
                        <div class="bg-blue-100 rounded-full p-4">
                            <i class="fa-solid fa-bolt text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Today --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Today's Activities</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $todayCount }}</h3>
                        </div>
                        <div class="bg-purple-100 rounded-full p-4">
                            <i class="fa-solid fa-calendar-day text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Admin / Super-admin --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-indigo-500 hover:shadow-xl transition-shadow cursor-pointer"
                     wire:click="$set('filterRole', 'admin')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Admin Actions</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">
                                {{ \App\Models\AccountActivity::whereHas('user.roles', fn($q) => $q->whereIn('name', ['admin','super-admin']))->count() }}
                            </h3>
                        </div>
                        <div class="bg-indigo-100 rounded-full p-4">
                            <i class="fa-solid fa-user-shield text-indigo-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Applicant --}}
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow cursor-pointer"
                     wire:click="$set('filterRole', 'applicant')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Applicant Actions</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">
                                {{ \App\Models\AccountActivity::whereHas('user.roles', fn($q) => $q->where('name', 'applicant'))->count() }}
                            </h3>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-4">
                            <i class="fa-solid fa-user-graduate text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Flash Messages ───────────────────────────────────────────────── --}}
            @if (session()->has('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                     class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <i class="fa-solid fa-xmark text-green-500 text-xl"></i>
                    </button>
                </div>
            @endif

            {{-- ── Table Card ───────────────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">

                {{-- Green header bar --}}
                <div class="bg-[#1E7F3E] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-bolt text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">
                                    @if($filterRole !== 'all')
                                        {{ ucfirst(str_replace('-', ' ', $filterRole)) }} Activities
                                    @else
                                        All Activities
                                    @endif
                                </h2>
                                @if($filterRole !== 'all' || $dateFrom || $dateTo || $search)
                                    <button wire:click="clearFilters"
                                            class="text-white/80 hover:text-white text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-xmark"></i> Clear All Filters
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            {{-- Role filter --}}
                            <select wire:model.live="filterRole"
                                    class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="all">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="super-admin">Super Admin</option>
                                <option value="panel">Panel</option>
                                <option value="nbc">NBC Committee</option>
                                <option value="applicant">Applicant</option>
                            </select>

                            {{-- Per page --}}
                            <select wire:model.live="perPage"
                                    class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="10">10 / page</option>
                                <option value="25">25 / page</option>
                                <option value="50">50 / page</option>
                                <option value="100">100 / page</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Table section --}}
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">

                                    {{-- ── Filters toolbar ──────────────────────────────── --}}
                                    <div class="px-6 py-4 border-b border-gray-300 space-y-3">

                                        {{-- Row 1: Search + Date range --}}
                                        <div class="flex flex-col md:flex-row gap-3 items-start md:items-center flex-wrap">

                                            {{-- Name / Email search --}}
                                            <div class="flex-1 min-w-[200px] max-w-sm">
                                                <label class="sr-only">Search</label>
                                                <div class="relative">
                                                    <input type="text"
                                                           wire:model.live.debounce.300ms="search"
                                                           class="py-2 px-3 ps-11 block w-full border border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]"
                                                           placeholder="Search by name or email…">
                                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                                    </div>
                                                    @if($search)
                                                        <button wire:click="$set('search', '')"
                                                                class="absolute inset-y-0 end-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                                            <i class="fa-solid fa-xmark text-xs"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Date From --}}
                                            <div class="flex items-center gap-2">
                                                <label class="text-sm text-gray-600 whitespace-nowrap font-medium">From:</label>
                                                <div class="relative">
                                                    <input type="date"
                                                           wire:model.live="dateFrom"
                                                           class="py-2 px-3 ps-9 block border border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]">
                                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                                        <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Date To --}}
                                            <div class="flex items-center gap-2">
                                                <label class="text-sm text-gray-600 whitespace-nowrap font-medium">To:</label>
                                                <div class="relative">
                                                    <input type="date"
                                                           wire:model.live="dateTo"
                                                           class="py-2 px-3 ps-9 block border border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]">
                                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                                        <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Clear dates --}}
                                            @if($dateFrom || $dateTo)
                                                <button wire:click="$set('dateFrom', ''); $set('dateTo', '')"
                                                        class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1 whitespace-nowrap">
                                                    <i class="fa-solid fa-calendar-xmark"></i> Clear dates
                                                </button>
                                            @endif
                                        </div>

                                        {{-- Row 2: info hint --}}
                                        <div class="flex items-center justify-end">
                                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                </span>
                                                New entries appear automatically
                                            </p>
                                        </div>
                                    </div>

                                    {{-- ── Table ────────────────────────────────────────── --}}
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th class="px-6 py-3 text-start w-12">
                                                    <span class="text-xs font-semibold uppercase text-black">No.</span>
                                                </th>
                                                <th class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Name</span>
                                                </th>
                                                <th class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Email</span>
                                                </th>
                                                <th class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Role</span>
                                                </th>
                                                <th class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Activity</span>
                                                </th>
                                                <th class="px-6 py-3 text-start whitespace-nowrap">
                                                    <span class="text-xs font-semibold uppercase text-black">Date &amp; Time</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            @forelse($activities as $index => $record)
                                                @php
                                                    $user     = $record->user;
                                                    $roleName = $user?->roles->first()?->name ?? 'N/A';

                                                    $badgeClass = match($roleName) {
                                                        'admin'       => 'bg-purple-100 text-purple-800',
                                                        'super-admin' => 'bg-indigo-100 text-indigo-800',
                                                        'panel'       => 'bg-blue-100 text-blue-800',
                                                        'nbc'         => 'bg-green-100 text-green-800',
                                                        'applicant'   => 'bg-yellow-100 text-yellow-800',
                                                        default       => 'bg-gray-100 text-gray-800',
                                                    };

                                                    if ($roleName === 'applicant' && $user?->applicant) {
                                                        $displayName = trim(
                                                            ($user->applicant->first_name  ?? '') . ' ' .
                                                            ($user->applicant->middle_name ?? '') . ' ' .
                                                            ($user->applicant->last_name   ?? '') . ' ' .
                                                            ($user->applicant->suffix      ?? '')
                                                        );
                                                    } else {
                                                        $displayName = $user?->name ?? '—';
                                                    }

                                                    // Truncate threshold (characters)
                                                    $activity    = $record->activity ?? '';
                                                    $threshold   = 80;
                                                    $isLong      = mb_strlen($activity) > $threshold;
                                                    $preview     = $isLong ? mb_substr($activity, 0, $threshold) . '…' : $activity;
                                                @endphp
                                                <tr class="bg-gray-50 hover:bg-gray-100 transition-colors">

                                                    {{-- No. --}}
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-medium">
                                                        {{ $activities->firstItem() + $index }}
                                                    </td>

                                                    {{-- Name --}}
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                        {{ $displayName }}
                                                    </td>

                                                    {{-- Email --}}
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                        {{ $user?->email ?? '—' }}
                                                    </td>

                                                    {{-- Role badge --}}
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                            {{ ucfirst(str_replace('-', ' ', $roleName)) }}
                                                        </span>
                                                    </td>

                                                    {{-- ── Activity with See more / See less ── --}}
                                                    <td class="px-6 py-4 text-sm text-gray-800 max-w-md">
                                                        @if($isLong)
                                                            <div x-data="{ expanded: false }">
                                                                <div class="flex items-start gap-2">
                                                                    <i class="fa-solid fa-circle-dot text-[#1E7F3E] text-xs mt-1 shrink-0"></i>
                                                                    <span class="break-words leading-relaxed">
                                                                        {{-- Collapsed: show preview --}}
                                                                        <span x-show="!expanded">{{ $preview }}</span>
                                                                        {{-- Expanded: show full text --}}
                                                                        <span x-show="expanded" x-cloak>{{ $activity }}</span>

                                                                        {{-- Toggle button --}}
                                                                        <button
                                                                            @click="expanded = !expanded"
                                                                            class="ml-1 text-[#1E7F3E] hover:text-[#156B2D] font-medium text-xs underline underline-offset-2 whitespace-nowrap focus:outline-none"
                                                                        >
                                                                            <span x-show="!expanded">See more <i class="fa-solid fa-chevron-down text-[10px]"></i></span>
                                                                            <span x-show="expanded" x-cloak>See less <i class="fa-solid fa-chevron-up text-[10px]"></i></span>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            {{-- Short text: no toggle needed --}}
                                                            <div class="flex items-start gap-2">
                                                                <i class="fa-solid fa-circle-dot text-[#1E7F3E] text-xs mt-1 shrink-0"></i>
                                                                <span class="break-words leading-relaxed">{{ $activity }}</span>
                                                            </div>
                                                        @endif
                                                    </td>

                                                    {{-- Date & Time --}}
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                        <span class="flex items-center gap-1.5">
                                                            <i class="fa-regular fa-clock text-gray-400 text-xs"></i>
                                                            {{ $record->datetime->format('M d, Y  h:i:s A') }}
                                                        </span>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-12 text-gray-500">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <i class="fa-solid fa-bolt text-gray-300 text-6xl mb-4"></i>
                                                            <p class="text-lg font-medium">No activity records found</p>
                                                            <p class="text-sm text-gray-400 mt-1">
                                                                @if($search || $filterRole !== 'all' || $dateFrom || $dateTo)
                                                                    No results match your current filters.
                                                                    <button wire:click="clearFilters" class="text-[#1E7F3E] underline ml-1">Clear filters</button>
                                                                @else
                                                                    Activities will appear here as users perform actions.
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    {{-- Pagination --}}
                                    <div class="p-4 bg-white border-t border-gray-300 flex flex-col sm:flex-row items-center justify-between gap-3">
                                        <span class="text-xs text-gray-500">
                                            @if ($activities->total() > 0)
                                                Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ $activities->total() }} {{ Str::plural('activity', $activities->total()) }}
                                            @else
                                                No activities found
                                            @endif
                                        </span>
                                        {{ $activities->links() }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>