<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            {{-- ── Header ──────────────────────────────────────────────────────── --}}
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-2">
                            Account Logs
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-[#1E7F3E]"></i>
                            Real-time login &amp; logout activity of all users
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

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Total Logs</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalLogs }}</h3>
                        </div>
                        <div class="bg-blue-100 rounded-full p-4">
                            <i class="fa-solid fa-list-check text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow cursor-pointer"
                     wire:click="$set('filterActivity', 'logged in')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Total Logins</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $loginCount }}</h3>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <i class="fa-solid fa-arrow-right-to-bracket text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500 hover:shadow-xl transition-shadow cursor-pointer"
                     wire:click="$set('filterActivity', 'logged out')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Total Logouts</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $logoutCount }}</h3>
                        </div>
                        <div class="bg-orange-100 rounded-full p-4">
                            <i class="fa-solid fa-arrow-right-from-bracket text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Today's Activity</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $todayCount }}</h3>
                        </div>
                        <div class="bg-purple-100 rounded-full p-4">
                            <i class="fa-solid fa-calendar-day text-purple-600 text-2xl"></i>
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
                                <i class="fa-solid fa-clock-rotate-left text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">
                                    @if($filterActivity === 'logged in')  Login Activity
                                    @elseif($filterActivity === 'logged out') Logout Activity
                                    @else All Activity
                                    @endif
                                </h2>
                                @if($filterActivity !== 'all' || $dateFrom || $dateTo || $search)
                                    <button wire:click="clearFilters"
                                            class="text-white/80 hover:text-white text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-xmark"></i> Clear All Filters
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
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
                                        <div class="flex flex-col md:flex-row gap-3 items-start md:items-center">

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

                                            {{-- Clear dates shortcut --}}
                                            @if($dateFrom || $dateTo)
                                                <button wire:click="$set('dateFrom', ''); $set('dateTo', '')"
                                                        class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1 whitespace-nowrap">
                                                    <i class="fa-solid fa-calendar-xmark"></i> Clear dates
                                                </button>
                                            @endif
                                        </div>

                                        {{-- Row 2: Activity filter pills --}}
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm text-gray-500 font-medium">Filter:</span>

                                            <button wire:click="$set('filterActivity', 'all')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition-all
                                                        {{ $filterActivity === 'all'
                                                            ? 'bg-[#1E7F3E] text-white border-[#1E7F3E]'
                                                            : 'bg-white text-gray-600 border-gray-300 hover:border-[#1E7F3E] hover:text-[#1E7F3E]' }}">
                                                <i class="fa-solid fa-list text-xs"></i>
                                                All
                                            </button>

                                            <button wire:click="$set('filterActivity', 'logged in')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition-all
                                                        {{ $filterActivity === 'logged in'
                                                            ? 'bg-green-600 text-white border-green-600'
                                                            : 'bg-white text-gray-600 border-gray-300 hover:border-green-500 hover:text-green-600' }}">
                                                <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                                                Logged In
                                                <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-bold
                                                    {{ $filterActivity === 'logged in' ? 'bg-white/30 text-white' : 'bg-green-100 text-green-700' }}">
                                                    {{ $loginCount }}
                                                </span>
                                            </button>

                                            <button wire:click="$set('filterActivity', 'logged out')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition-all
                                                        {{ $filterActivity === 'logged out'
                                                            ? 'bg-orange-500 text-white border-orange-500'
                                                            : 'bg-white text-gray-600 border-gray-300 hover:border-orange-400 hover:text-orange-500' }}">
                                                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                                                Logged Out
                                                <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-bold
                                                    {{ $filterActivity === 'logged out' ? 'bg-white/30 text-white' : 'bg-orange-100 text-orange-700' }}">
                                                    {{ $logoutCount }}
                                                </span>
                                            </button>

                                            {{-- <p class="ml-auto text-xs text-gray-400 flex items-center gap-1">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                </span>
                                                New entries appear automatically
                                            </p> --}}
                                        </div>
                                    </div>

                                    {{-- ── Table ────────────────────────────────────────── --}}
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th class="px-6 py-3 text-start">
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
                                                <th class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Date &amp; Time</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            @forelse($logs as $index => $log)
                                                @php
                                                    $user     = $log->user;
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

                                                    $activityClass = $log->activity === 'logged in'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-orange-100 text-orange-700';

                                                    $activityIcon = $log->activity === 'logged in'
                                                        ? 'fa-arrow-right-to-bracket'
                                                        : 'fa-arrow-right-from-bracket';
                                                @endphp
                                                <tr class="bg-gray-50 hover:bg-gray-100 transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-medium">
                                                        {{ $logs->firstItem() + $index }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                        {{ $displayName }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                        {{ $user?->email ?? '—' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                            {{ ucfirst(str_replace('-', ' ', $roleName)) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $activityClass }}">
                                                            <i class="fa-solid {{ $activityIcon }} text-xs"></i>
                                                            {{ ucfirst($log->activity) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                        <span class="flex items-center gap-1.5">
                                                            <i class="fa-regular fa-clock text-gray-400 text-xs"></i>
                                                            {{ $log->datetime->format('M d, Y  h:i:s A') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-12 text-gray-500">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <i class="fa-solid fa-clock-rotate-left text-gray-300 text-6xl mb-4"></i>
                                                            <p class="text-lg font-medium">No activity logs found</p>
                                                            <p class="text-sm text-gray-400 mt-1">
                                                                @if($search || $filterActivity !== 'all' || $dateFrom || $dateTo)
                                                                    No results match your current filters.
                                                                    <button wire:click="clearFilters" class="text-[#1E7F3E] underline ml-1">Clear filters</button>
                                                                @else
                                                                    Logs will appear here as users log in or out.
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
                                            @if ($logs->total() > 0)
                                                Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} log{{ $logs->total() !== 1 ? 's' : '' }}
                                            @else
                                                No logs found
                                            @endif
                                        </span>
                                        {{ $logs->links() }}
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