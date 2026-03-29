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
                    <a href="{{ route('admin.position.history') }}"
                       class="inline-flex items-center gap-2 bg-white border border-[#0a6025] text-[#0a6025] hover:bg-[#0a6025] hover:text-white transition-colors duration-200 font-medium rounded-lg text-sm px-5 py-2.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Position History
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
                <!-- Table Header with Filters -->
                <div class="bg-[#0a6025] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-briefcase text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Position List</h2>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <!-- College Filter -->
                            <select wire:model.live="filterCollege"
                                x-data x-bind:class="$el.value === '' ? 'text-gray-400' : 'text-gray-900'"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium focus:ring-2 focus:ring-white">
                                <option value="">All Colleges</option>
                                <option value="various">Various Colleges</option>
                                @foreach($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>

                            <!-- Department Filter -->
                            <select wire:model.live="filterDepartment"
                                x-data x-bind:class="$el.value === '' ? 'text-gray-400' : 'text-gray-900'"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium focus:ring-2 focus:ring-white">
                                <option value="">All Departments</option>
                                <option value="various">Various Departments</option>
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
                                    <!-- Search + Create -->
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
                                                    {{ $position->college->name ?? 'Various Colleges' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">
                                                    {{ $position->department->name ?? 'Various Departments' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">{{ $position->start_date }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-black">{{ $position->end_date }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap flex items-center gap-1">
                                                    {{-- View --}}
                                                    <button wire:click="showPosition({{ $position->id }})"
                                                        class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                                                        View
                                                    </button>
                                                    {{-- Edit --}}
                                                    <a href="{{ route('admin.position.edit', $position->id) }}"
                                                        class="text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg px-3 py-1 text-sm font-medium inline-block">
                                                        Edit
                                                    </a>
                                                    {{-- Delete --}}
                                                    <button wire:click="deleteConfirmed({{ $position->id }})"
                                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-8 text-gray-500">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                        </path>
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

    <!-- ── Position Details Modal ──────────────────────────────────────────── -->
    @if($showModal && $viewPosition)
    <div class="fixed inset-0 z-50 flex items-center justify-center"
         x-data
         x-on:keydown.escape.window="$wire.closeModal()">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             wire:click="closeModal"></div>

        {{-- Panel --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden animate-fadeIn">

            {{-- Modal Header --}}
            <div class="bg-[#0a6025] px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 rounded-lg p-2">
                        <i class="fa-solid fa-briefcase text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Position Details</h3>
                        <p class="text-green-200 text-sm">Full information for this position</p>
                    </div>
                </div>
                <button wire:click="closeModal"
                    class="text-white/70 hover:text-white transition-colors rounded-lg p-1 hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-6 space-y-4 max-h-[70vh] overflow-y-auto">

                {{-- Row helper: two-column grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Position Name --}}
                    <div class="sm:col-span-2 bg-green-50 border border-green-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-green-700 mb-1">Position Name</p>
                        <p class="text-gray-900 font-semibold text-lg">{{ $viewPosition->name }}</p>
                    </div>

                    {{-- College --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">College</p>
                        <p class="text-gray-800 font-medium">{{ $viewPosition->college->name ?? 'Various Colleges' }}</p>
                    </div>

                    {{-- Department --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Department</p>
                        <p class="text-gray-800 font-medium">{{ $viewPosition->department->name ?? 'Various Departments' }}</p>
                    </div>

                    {{-- Specialization --}}
                    <div class="sm:col-span-2 bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Specialization</p>
                        <p class="text-gray-800 font-medium">{{ $viewPosition->specialization }}</p>
                    </div>

                    {{-- Education --}}
                    <div class="sm:col-span-2 bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Education</p>
                        <p class="text-gray-800 font-medium">{{ $viewPosition->education }}</p>
                    </div>

                    {{-- Eligibility --}}
                    <div class="sm:col-span-2 bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Eligibility</p>
                        <p class="text-gray-800 font-medium">{{ $viewPosition->eligibility }}</p>
                    </div>

                    {{-- Experience --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Experience Required</p>
                        <p class="text-gray-800 font-medium">
                            {{ $viewPosition->experience }}
                            {{ Str::plural('year', $viewPosition->experience) }}
                        </p>
                    </div>

                    {{-- Training --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Training Required</p>
                        <p class="text-gray-800 font-medium">
                            {{ $viewPosition->training }}
                            {{ Str::plural('hour', $viewPosition->training) }}
                        </p>
                    </div>

                    {{-- Start Date --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Start Date</p>
                        <p class="text-gray-800 font-medium">
                            {{ $viewPosition->start_date
                                ? \Carbon\Carbon::parse($viewPosition->start_date)->format('F j, Y')
                                : '—' }}
                        </p>
                    </div>

                    {{-- End Date --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500 mb-1">End Date</p>
                        <p class="text-gray-800 font-medium">
                            {{ $viewPosition->end_date
                                ? \Carbon\Carbon::parse($viewPosition->end_date)->format('F j, Y')
                                : '—' }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('admin.position.edit', $viewPosition->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <button wire:click="closeModal"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 text-sm font-medium transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
    <!-- ───────────────────────────────────────────────────────────────────── -->
</div>