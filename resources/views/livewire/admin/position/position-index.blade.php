<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
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
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-90"
                     class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg animate-slideInDown" 
                     role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold">Success!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="ml-4 text-green-700 hover:text-green-900">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-90"
                     class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg animate-slideInDown" 
                     role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold">Error!</p>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="ml-4 text-red-700 hover:text-red-900">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Table Card -->
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

                        <!-- Right: Search + Filter -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Filter -->
                            <select wire:model.live="filter"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="all">All Status</option>
                                <option value="vacant">Vacant</option>
                                <option value="promotion">Promotion</option>
                                <option value="none">None</option>
                            </select>

                            <!-- College Filter (using college_id) -->
                            <select wire:model.live="filterCollege"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white">
                                <option value="">All Colleges</option>
                                @foreach($colleges as $college)
                                {{-- Use college ID as value instead of name --}}
                                <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>

                            <!-- Department Filter (dynamic based on college) -->
                            <select wire:model.live="filterDepartment"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white">
                                <option value="">All Departments</option>
                                @foreach($filterDepartments as $dept)
                                {{-- Use department ID as value instead of name --}}
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

                <!-- Table Section -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
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
                                                    <svg class="shrink-0 size-4 text-gray-400"
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
                                            <a href="{{ route('admin.position.create') }}" 
                                               class="block text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 
                                                      font-medium rounded-lg text-sm px-5 py-2.5">
                                                Create Position
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Name</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">College</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Department</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Status</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Start Date</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">End Date</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Action</span>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            @forelse($positions as $position)
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-black">{{ $position->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">
                                                    {{-- Display college name through relationship --}}
                                                    {{ $position->college->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">
                                                    {{-- Display department name through relationship --}}
                                                    {{ $position->department->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">
                                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                                        @if($position->status === 'vacant') bg-amber-100 text-amber-800
                                                        @elseif($position->status === 'promotion') bg-emerald-100 text-emerald-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst($position->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">{{ $position->start_date }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">{{ $position->end_date }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <a href="{{ route('admin.position.edit', $position->id) }}"
                                                        class="text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg px-3 py-1 text-sm font-medium inline-block">
                                                        Edit
                                                    </a>
                                                    <button wire:click="deleteConfirmed({{ $position->id }})"
                                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-8 text-gray-500">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <p class="mt-2">No positions found</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="p-4">
                                        {{ $positions->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>