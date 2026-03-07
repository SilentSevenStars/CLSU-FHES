<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Position History
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Expired Positions
                        </p>
                    </div>

                    <!-- Back Button -->
                    <a href="{{ route('admin.position') }}"
                       class="inline-flex items-center gap-2 bg-white border border-[#0a6025] text-[#0a6025] hover:bg-[#0a6025] hover:text-white transition-colors duration-200 font-medium rounded-lg text-sm px-5 py-2.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Positions
                    </a>
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
                     class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg"
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
                     class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg"
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
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                <!-- Table Header with Filters -->
                <div class="bg-[#0a6025] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <!-- Left: Title -->
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Position History List</h2>
                        </div>

                        <!-- Right: Filters -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- College Filter -->
                            <select wire:model.live="filterCollege"
                                x-data x-bind:class="$el.value === '' ? 'text-gray-400' : 'text-gray-900'"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium focus:ring-2 focus:ring-white">
                                <option value="" class="text-gray-400">All Colleges</option>
                                @foreach($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>

                            <!-- Department Filter -->
                            <select wire:model.live="filterDepartment"
                                x-data x-bind:class="$el.value === '' ? 'text-gray-400' : 'text-gray-900'"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium focus:ring-2 focus:ring-white">
                                <option value="" class="text-gray-400">All Departments</option>
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

                <!-- Table Section -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                    <!-- Search -->
                                    <div class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-300 gap-3">
                                        <div class="flex-1 min-w-[200px] max-w-md">
                                            <label class="sr-only">Search</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live="search"
                                                    class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500"
                                                    placeholder="Search by name or department...">
                                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                    <svg class="shrink-0 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Expired badge indicator -->
                                        {{-- <div class="flex items-center gap-2 text-sm text-gray-500">
                                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-3 py-1 rounded-full font-medium">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                Showing expired positions only
                                            </span>
                                        </div> --}}
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
                                                    <span class="text-xs font-semibold uppercase text-black">Start Date</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">End Date</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Status</span>
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
                                                    {{ $position->college->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">
                                                    {{ $position->department->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">{{ $position->start_date }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">{{ $position->end_date }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-medium">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Expired
                                                    </span>
                                                </td>
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
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    <p class="mt-2">No expired positions found</p>
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