<div x-data="{
    showModal: @entangle('showModal')
}"
@alert.window="
    Swal.fire({
        icon: $event.detail.type,
        title: $event.detail.title,
        text: $event.detail.text,
        position: $event.detail.position || 'center',
        showConfirmButton: true,
        timer: 3000
    });
"
@confirmation.window="
    Swal.fire({
        title: $event.detail.title,
        text: $event.detail.text,
        icon: $event.detail.icon,
        showCancelButton: $event.detail.showCancelButton,
        confirmButtonColor: $event.detail.confirmButtonColor,
        cancelButtonColor: $event.detail.cancelButtonColor,
        confirmButtonText: $event.detail.confirmButtonText
    }).then((result) => {
        if (result.isConfirmed) {
            $wire.dispatch('destroy', { id: $event.detail.id });
        }
    });
">

    <div class="flex-1 bg-gradient-to-br from-slate-50 via-cyan-50 to-teal-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-cyan-600 bg-clip-text text-transparent mb-2">
                            Departments
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Manage Departments
                        </p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                <!-- Table Header -->
                <div class="bg-cyan-600 p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-building text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Departments List</h2>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
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
                                                    class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500"
                                                    placeholder="Search departments or colleges...">
                                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                                    <svg class="shrink-0 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Create Button -->
                                        <div>
                                            <button wire:click="create" class="block text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300 
                                                font-medium rounded-lg text-sm px-5 py-2.5">
                                                Create Department
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">ID</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Department Name</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">College</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Created Date</span>
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-start">
                                                    <span class="text-xs font-semibold uppercase text-black">Action</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300 bg-gray-50">
                                            @forelse($departments as $department)
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-medium">
                                                    {{ $department->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{ $department->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{-- Display college name through relationship --}}
                                                    {{-- Uses department->college relationship (belongsTo) --}}
                                                    {{ $department->college->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{ $department->created_at->format('M d, Y h:i A') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button wire:click="edit({{ $department->id }})"
                                                        class="text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg px-3 py-1 text-sm font-medium">
                                                        Edit
                                                    </button>
                                                    <button wire:click="deleteConfirmed({{ $department->id }})"
                                                        class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-8 text-gray-500">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                        <p class="text-lg font-medium">No departments found</p>
                                                        <button wire:click="create" class="mt-4 text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg px-4 py-2 text-sm font-medium">
                                                            Create First Department
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="p-4">
                                        {{ $departments->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         style="background-color: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click.away="$wire.closeModal()" class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="bg-cyan-600 px-6 py-4 rounded-t-lg">
                    <h3 class="text-xl font-bold text-white">
                        {{ $editMode ? 'Edit' : 'Create' }} Department
                    </h3>
                </div>
                <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                    <div class="p-6">
                        <div class="mb-4">
                            <label for="college_id" class="block text-sm font-medium text-gray-700 mb-2">
                                College <span class="text-red-500">*</span>
                            </label>
                            {{-- Dropdown to select college using college_id foreign key --}}
                            {{-- Value is college.id which will be stored in departments.college_id --}}
                            <select wire:model="college_id" 
                                    id="college_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('college_id') border-red-500 @enderror">
                                <option value="">-- Select College --</option>
                                @foreach($colleges as $college)
                                <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                            @error('college_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Department Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="name" 
                                   id="name"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., Computer Science Department">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 font-medium">
                            {{ $editMode ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>