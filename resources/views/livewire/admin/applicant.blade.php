<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section with Enhanced Styling -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Applicants
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Review and manage applicants
                        </p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Pending Card -->
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-amber-500 transform hover:-translate-y-1 animate-slideInLeft"
                    style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Pending This Month
                            </p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                {{ $pendingCount }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">Pending</p>
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

                <!-- Approved Card -->
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-emerald-500 transform hover:-translate-y-1 animate-slideInLeft"
                    style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Approved This Month
                            </p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                {{ $approvedCount }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">Approved</p>
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

                <!-- Declined Card -->
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-red-500 transform hover:-translate-y-1 animate-slideInLeft"
                    style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Declined This Month
                            </p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300">
                                {{ $declinedCount }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">Declined</p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session()->has('success'))
            <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800 leading-5 tracking-wide">{{ session('success')
                    }}</p>
            </div>
            @endif

            @if(session()->has('error'))
            <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800 leading-5 tracking-wide">{{ session('error') }}
                </p>
            </div>
            @endif

            <!-- Enhanced Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.4s;">
                <!-- Table Header with Filter -->
                <div class="bg-[#0a6025] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <!-- Left: Title -->
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-users text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Applicant List</h2>
                        </div>

                        <!-- Right: Per Page -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Status Filter -->
                            <select wire:model.live="status"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700">
                                <option value="pending">Pending</option>
                                <option value="approve">Approved</option>
                                <option value="decline">Declined</option>
                            </select>

                            <!-- College Filter -->
                            <select wire:model.live="college_id"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700">
                                <option value="">Select College</option>
                                @foreach ($colleges as $college)
                                <option value="{{ $college->name }}">{{ $college->name }}</option>
                                @endforeach
                            </select>

                            <!-- Department Filter -->
                            <select wire:model.live="department_id"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700" {{
                                !$college_id ? 'disabled' : '' }}>
                                <option value="">Select Department</option>
                                @foreach ($departments as $dept)
                                <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>

                            <!-- Position Filter -->
                            <select wire:model.live="position"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700" {{
                                !$department_id ? 'disabled' : '' }}>
                                <option value="">Select Position</option>
                                @foreach ($positions as $pos)
                                <option value="{{ $pos->name }}">{{ $pos->name }}</option>
                                @endforeach
                            </select>

                            <!-- Per Page -->
                            <select wire:model.live="perPage"
                                class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700">
                                <option value="5">5 / page</option>
                                <option value="10">10 / page</option>
                                <option value="15">15 / page</option>
                                <option value="20">20 / page</option>
                            </select>
                        </div>
                    </div>
                </div>

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
                                            <label for="hs-as-table-applicant-search" class="sr-only">Search</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live="search"
                                                    class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500"
                                                    placeholder="Search by name, email, or position...">
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
                                    </div>
                                    <!-- End Header -->

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-black">#</span>
                                                    </div>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            Applicant Name
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            Email
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            Position
                                                        </span>
                                                    </div>
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <div class="flex items-center gap-x-2">
                                                        <span class="text-xs font-semibold uppercase text-black">
                                                            Date Applied
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
                                                            Action
                                                        </span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            @forelse($applications as $application)
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-semibold text-black">
                                                        {{ $applications->firstItem() ? $applications->firstItem() +
                                                        $loop->index : $loop->iteration }}
                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-semibold text-black">
                                                        {{ $application->applicant->user->name }}
                                                    </div>
                                                </td>

                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        {{ $application->applicant->user->email }}
                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        {{ $application->position->name }}
                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <div class="text-sm font-medium text-black">
                                                        {{ $application->created_at->format('M j, Y') }}
                                                    </div>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <span
                                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $application->status === 'approve' ? 'bg-green-100 text-green-800' : '' }}
                                                        {{ $application->status === 'decline' ? 'bg-red-100 text-red-800' : '' }}
                                                        {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                </td>
                                                <td class="size-px whitespace-nowrap align-top">
                                                    <a href="{{ route('admin.applicant.show', $application) }}"
                                                        class="inline-flex items-center px-4 py-2 bg-[#0a6025] text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                            <path fill-rule="evenodd"
                                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Review
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center">
                                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p class="text-black text-lg font-medium">No applicant applications
                                                        found</p>
                                                    <p class="text-gray-600 text-sm mt-1">Applications will appear here
                                                        once submitted</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <!-- End Table -->
                                    <div class="p-4">
                                        {{ $applications->links() }}
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
</div>