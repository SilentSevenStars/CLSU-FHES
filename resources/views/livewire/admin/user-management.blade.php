<div
    x-data="{
        showModal: @entangle('showModal'),
        showArchiveModal: @entangle('showArchiveModal'),
        filterRole: @entangle('filterRole')
    }"
>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                     class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <i class="fa-solid fa-xmark text-green-500 text-xl"></i>
                    </button>
                </div>
            @endif

            @if (session()->has('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <i class="fa-solid fa-xmark text-red-500 text-xl"></i>
                    </button>
                </div>
            @endif

            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#1E7F3E] bg-clip-text text-transparent mb-2">
                            User Management
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <i class="fa-solid fa-users-gear w-5 h-5 text-[#1E7F3E]"></i>
                            Manage all system users
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8 animate-fadeIn">
                <!-- Total Users -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Total Users</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</h3>
                        </div>
                        <div class="bg-blue-100 rounded-full p-4">
                            <i class="fa-solid fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Admin Users -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow cursor-pointer"
                     wire:click="$set('filterRole', 'admin')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Admins</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $adminCount }}</h3>
                        </div>
                        <div class="bg-purple-100 rounded-full p-4">
                            <i class="fa-solid fa-user-shield text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Super Admin Users -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-indigo-500 hover:shadow-xl transition-shadow cursor-pointer"
                     wire:click="$set('filterRole', 'super-admin')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Super Admins</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $superAdminCount }}</h3>
                        </div>
                        <div class="bg-indigo-100 rounded-full p-4">
                            <i class="fa-solid fa-user-tie text-indigo-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Panel Members -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow cursor-pointer"
                     wire:click="$set('filterRole', 'panel')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Panel</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $panelCount }}</h3>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <i class="fa-solid fa-people-group text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- NBC Committee -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-emerald-500 hover:shadow-xl transition-shadow cursor-pointer"
                     wire:click="$set('filterRole', 'nbc')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">NBC</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $nbcCount }}</h3>
                        </div>
                        <div class="bg-emerald-100 rounded-full p-4">
                            <i class="fa-solid fa-clipboard-check text-emerald-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Applicants -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow cursor-pointer md:col-span-2 lg:col-span-5"
                     wire:click="$set('filterRole', 'applicant')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Applicants</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $applicantCount }}</h3>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-4">
                            <i class="fa-solid fa-user-graduate text-yellow-600 text-2xl"></i>
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
                                <i class="fa-solid fa-users text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">
                                    @if($filterRole === 'all')
                                        All Users
                                    @elseif($filterRole === 'admin')
                                        Admin Users
                                    @elseif($filterRole === 'super-admin')
                                        Super Admin Users
                                    @elseif($filterRole === 'panel')
                                        Panel Members
                                    @elseif($filterRole === 'nbc')
                                        NBC Committee
                                    @elseif($filterRole === 'applicant')
                                        Applicants
                                    @endif
                                </h2>
                                @if($filterRole !== 'all')
                                    <button wire:click="$set('filterRole', 'all')" 
                                            class="text-white/80 hover:text-white text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-xmark"></i>
                                        Clear Filter
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Role Filter -->
                            <select wire:model.live="filterRole"
                                    class="bg-white/90 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-white focus:outline-none">
                                <option value="all">All Users</option>
                                <option value="admin">Admin</option>
                                <option value="super-admin">Super Admin</option>
                                <option value="panel">Panel</option>
                                <option value="nbc">NBC Committee</option>
                                <option value="applicant">Applicant</option>
                            </select>

                            <!-- Per Page -->
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

                <!-- Table Section -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="bg-gray-50 border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                                    <!-- Header -->
                                    <div class="px-6 py-4 flex flex-wrap items-center justify-between border-b border-gray-300 gap-3">
                                        <!-- Search Input -->
                                        <div class="flex-1 min-w-[200px] max-w-md">
                                            <label class="sr-only">Search</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live.debounce.300ms="search"
                                                       class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]"
                                                       placeholder="Search by name or email...">
                                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Create Dropdown -->
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" @click.away="open = false"
                                                    class="text-white bg-[#156B2D] hover:bg-[#125A26] focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                                                Add New User
                                                <i class="fa-solid fa-chevron-down ml-2 w-4 h-4"></i>
                                            </button>
                                            <div x-show="open" x-cloak
                                                 class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                <div class="py-1">
                                                    <button wire:click="openCreateModal('regular')" @click="open = false"
                                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fa-solid fa-user-shield mr-2"></i> Admin User
                                                    </button>
                                                    <button wire:click="openCreateModal('panel')" @click="open = false"
                                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fa-solid fa-users mr-2"></i> Panel Member
                                                    </button>
                                                    <button wire:click="openCreateModal('nbc')" @click="open = false"
                                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fa-solid fa-clipboard-check mr-2"></i> NBC Committee
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">No.</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Name</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Email</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Role</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Details</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Action</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            @forelse($users as $index => $user)
                                                @php
                                                    $roleName = $user->roles->first()?->name ?? 'none';
                                                    $badgeClass = match($roleName) {
                                                        'admin'       => 'bg-purple-100 text-purple-800',
                                                        'super-admin' => 'bg-indigo-100 text-indigo-800',
                                                        'panel'       => 'bg-blue-100 text-blue-800',
                                                        'nbc'         => 'bg-green-100 text-green-800',
                                                        'applicant'   => 'bg-yellow-100 text-yellow-800',
                                                        default       => 'bg-amber-100 text-amber-800',
                                                    };

                                                    // Get display name
                                                    if ($roleName === 'applicant' && $user->applicant) {
                                                        $displayName = trim($user->applicant->first_name . ' ' . ($user->applicant->middle_name ?? '') . ' ' . $user->applicant->last_name . ' ' . ($user->applicant->suffix ?? ''));
                                                    } else {
                                                        $displayName = $user->name;
                                                    }

                                                    // Get details based on role
                                                    $details = '';
                                                    if ($roleName === 'panel' && $user->panel) {
                                                        $details = ucfirst($user->panel->panel_position) . ' - ' . ($user->panel->college->name ?? 'N/A');
                                                        if ($user->panel->department) {
                                                            $details .= ' (' . $user->panel->department->name . ')';
                                                        }
                                                    } elseif ($roleName === 'nbc' && $user->nbcCommittee) {
                                                        $details = ucfirst($user->nbcCommittee->position);
                                                    }
                                                @endphp
                                                <tr class="bg-gray-50 hover:bg-gray-100">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-medium">
                                                        {{ $users->firstItem() + $index }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                        {{ $displayName }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                        {{ $user->email }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                            {{ ucfirst(str_replace('-', ' ', $roleName)) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-black">
                                                        {{ $details ?: '-' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <button wire:click="openEditModal({{ $user->id }}, '{{ $roleName }}')"
                                                                class="text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg px-3 py-1 text-sm font-medium">
                                                            Edit
                                                        </button>
                                                        <button wire:click="openArchiveModal({{ $user->id }})"
                                                                class="px-3 py-1 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700">
                                                            Archive
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-8 text-gray-500">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <i class="fa-solid fa-users-slash text-gray-400 text-6xl mb-4"></i>
                                                            <p class="text-lg font-medium">No users found</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="p-4 bg-white border-t border-gray-300 flex flex-col sm:flex-row items-center justify-between gap-3">
                                        <span class="text-xs text-gray-500">
                                            @if ($users->total() > 0)
                                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} user{{ $users->total() !== 1 ? 's' : '' }}
                                            @else
                                                No users found
                                            @endif
                                        </span>
                                        {{ $users->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CREATE / EDIT MODAL -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$wire.closeModal()"></div>

            <!-- Modal panel -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 @click.away="$wire.closeModal()">
                
                <div class="bg-[#1E7F3E] px-6 py-4">
                    <h3 class="text-xl font-bold text-white">
                        {{ $isEditMode ? 'Edit' : 'Create' }} 
                        @if($filterRole === 'panel') Panel Member
                        @elseif($filterRole === 'nbc') NBC Committee
                        @elseif($filterRole === 'applicant') Applicant
                        @else User
                        @endif
                    </h3>
                </div>

                <form wire:submit.prevent="save">
                    <div class="bg-white px-6 pt-5 pb-4 max-h-[70vh] overflow-y-auto">
                        
                        <!-- Applicant Fields -->
                        @if($filterRole === 'applicant')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                                <input wire:model="first_name" type="text"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('first_name') border-red-500 @enderror">
                                @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                <input wire:model="middle_name" type="text"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E]">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                                <input wire:model="last_name" type="text"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('last_name') border-red-500 @enderror">
                                @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Suffix (Jr., Sr., III, etc.)</label>
                                <input wire:model="suffix" type="text"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E]">
                            </div>

                        @elseif($filterRole === 'panel')
                            <!-- Panel Fields -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                                <input wire:model="name" type="text"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('name') border-red-500 @enderror">
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Panel Position <span class="text-red-500">*</span></label>
                                <select wire:model.live="panel_position"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('panel_position') border-red-500 @enderror">
                                    <option value="">Select Position</option>
                                    <option value="head">Head</option>
                                    <option value="señior">Senior</option>
                                    <option value="dean">Dean</option>
                                </select>
                                @error('panel_position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">College <span class="text-red-500">*</span></label>
                                <select wire:model="college_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('college_id') border-red-500 @enderror">
                                    <option value="">Select College</option>
                                    @foreach($colleges as $college)
                                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                                @error('college_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            @if($panel_position !== 'dean')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Department <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="department_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('department_id') border-red-500 @enderror">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            @endif

                        @elseif($filterRole === 'nbc')
                            <!-- NBC Committee Fields -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                                <input wire:model="name" type="text"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('name') border-red-500 @enderror">
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Position <span class="text-red-500">*</span></label>
                                <select wire:model="nbc_position"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('nbc_position') border-red-500 @enderror">
                                    <option value="">Select Position</option>
                                    <option value="evaluator">Evaluator</option>
                                    <option value="verifier">Verifier</option>
                                </select>
                                @error('nbc_position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                        @else
                            <!-- Regular User Fields -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                                <input wire:model="name" type="text"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('name') border-red-500 @enderror">
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                                <select wire:model="role"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('role') border-red-500 @enderror">
                                    <option value="">Select Role</option>
                                    @foreach ($availableRoles as $availableRole)
                                        <option value="{{ $availableRole->name }}">
                                            {{ ucfirst(str_replace('-', ' ', $availableRole->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <!-- Common Fields -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input wire:model="email" type="email"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('email') border-red-500 @enderror">
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                                @if ($isEditMode)
                                    <span class="text-gray-400 font-normal">(Leave blank to keep current)</span>
                                @else
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            <input wire:model="password" type="password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E] @error('password') border-red-500 @enderror">
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input wire:model="password_confirmation" type="password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E7F3E]">
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-[#1E7F3E] text-white rounded-lg hover:bg-[#156B2D] font-medium">
                            {{ $isEditMode ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ARCHIVE CONFIRMATION MODAL -->
    <div x-show="showArchiveModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showArchiveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$wire.closeArchiveModal()"></div>

            <div x-show="showArchiveModal" x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 @click.away="$wire.closeArchiveModal()">
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-box-archive text-orange-600 text-2xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Archive User</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to archive this user? The user will no longer be able to log in and will be hidden from the users list. This action can be reversed later.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closeArchiveModal" type="button"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                        Cancel
                    </button>
                    <button wire:click="archive" type="button"
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium">
                        Archive
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>