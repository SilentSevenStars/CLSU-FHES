<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section with Enhanced Styling -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Position
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Manage Position
                        </p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- Vacant Card -->
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-amber-500 transform hover:-translate-y-1 animate-slideInLeft"
                    style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Vacant Position</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                {{ $vacant }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">Vacant</p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300 relative">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Promotion Card -->
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-emerald-500 transform hover:-translate-y-1 animate-slideInLeft"
                    style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Promotion</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                {{ $promotion }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">Promotion</p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Enhanced Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.3s;">
                <!-- Table Header with Filter -->
                <div class="bg-[#0a6025] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <!-- Left: Title -->
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-briefcase text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Position List</h2>
                        </div>

                        <!-- Right: Search + Filter + Create -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Status Filter -->
                            <select wire:model.live="filter"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="all">All Status</option>
                                <option value="vacant">Vacant</option>
                                <option value="promotion">Promotion</option>
                                <option value="none">None</option>
                            </select>

                            <!-- College Filter (using college_id) -->
                            {{-- Filter by college using foreign key --}}
                            <select wire:model.live="filterCollege"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white">
                                <option value="">All Colleges</option>
                                @foreach($colleges as $college)
                                <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>

                            <!-- Department Filter (dynamic based on college selection) -->
                            {{-- Filter by department using foreign key, filtered by selected college --}}
                            <select wire:model.live="filterDepartment"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white">
                                <option value="">All Departments</option>
                                @foreach($filterDepartments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>

                            <!-- Per Page -->
                            <select wire:model.live="perPage"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="5">5 / page</option>
                                <option value="10">10 / page</option>
                                <option value="15">15 / page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <!-- Table Section -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <!-- Card -->
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                    <!-- Header -->
                                    <div
                                        class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-300 gap-3">
                                        <!-- Search Input -->
                                        <div class="flex-1 min-w-[200px] max-w-md">
                                            <label for="hs-as-table-product-review-search"
                                                class="sr-only">Search</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live="search"
                                                    class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500"
                                                    placeholder="Search by name or department...">
                                                <div
                                                    class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                    <svg class="shrink-0 size-4 text-gray-400 dark:text-neutral-500"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Create Button -->
                                        <div>
                                            <button wire:click="openCreateModal" class="block text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 
                                        font-medium rounded-lg text-sm px-5 py-2.5">
                                                Create Position
                                            </button>
                                        </div>
                                    </div>
                                    <!-- End Header -->

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            Name
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            College
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            Department
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            Status
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            Start Date
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            End Date
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            Action
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            @forelse($positions as $position)
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{ $position->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{-- Display college name through relationship --}}
                                                    {{-- Uses position->college relationship (belongsTo) --}}
                                                    {{ $position->college->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{-- Display department name through relationship --}}
                                                    {{-- Uses position->department relationship (belongsTo) --}}
                                                    {{ $position->department->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                                        @if($position->status === 'vacant') bg-amber-100 text-amber-800
                                                        @elseif($position->status === 'promotion') bg-emerald-100 text-emerald-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst($position->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{ $position->start_date ? \Carbon\Carbon::parse($position->start_date)->format('M d, Y') : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{ $position->end_date ? \Carbon\Carbon::parse($position->end_date)->format('M d, Y') : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button wire:click="openEditModal({{ $position->id }})"
                                                        class="text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg px-3 py-1 text-sm font-medium">
                                                        Edit
                                                    </button>
                                                    <button wire:click="deleteConfirmed({{ $position->id }})"
                                                        class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-8 text-gray-500">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                            </path>
                                                        </svg>
                                                        <p class="text-lg font-medium">No positions found</p>
                                                        <button wire:click="openCreateModal" class="mt-4 text-white bg-green-600 hover:bg-green-700 rounded-lg px-4 py-2 text-sm font-medium">
                                                            Create First Position
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <!-- End Table -->
                                    <div class="p-4">
                                        {{ $positions->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Table Section -->
            </div>

        </div>
    </div>

    @if($showCreateModal)
    @include('livewire.admin.modals.create-position')
    @endif

    @if($showEditModal)
    @include('livewire.admin.modals.edit-position')
    @endif
</div>