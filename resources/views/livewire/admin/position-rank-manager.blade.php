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
            $wire.call('delete');
        }
    });
">

    <div class="flex-1 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Position Ranks
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0a6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                </path>
                            </svg>
                            Manage Position Ranks
                        </p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                <!-- Table Header -->
                <div class="bg-[#0a6025] p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                                <i class="fa-solid fa-layer-group text-white text-lg"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Position Ranks List</h2>
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
                                                    class="py-2 px-3 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-green-700 focus:ring-green-700"
                                                    placeholder="Search position ranks...">
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
                                            <button wire:click="create" class="block text-white bg-[#1E7F3E] hover:bg-[#138032] focus:ring-4 focus:ring-blue-300 
                                                font-medium rounded-lg text-sm px-5 py-2.5">
                                                Create Rank
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
                                                    <span class="text-xs font-semibold uppercase text-black">Rank Name</span>
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
                                            @forelse($positionRanks as $rank)
                                            <tr class="bg-gray-50 hover:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black font-medium">
                                                    {{ $rank->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{ $rank->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                    {{ $rank->created_at->format('M d, Y h:i A') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button wire:click="edit({{ $rank->id }})"
                                                        class="text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg px-3 py-1 text-sm font-medium">
                                                        Edit
                                                    </button>
                                                    <button wire:click="confirmDelete({{ $rank->id }})"
                                                        class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-8 text-gray-500">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                        </svg>
                                                        <p class="text-lg font-medium">No position ranks found</p>
                                                        <button wire:click="create" class="mt-4 text-white bg-green-600 hover:bg-green-800 rounded-lg px-4 py-2 text-sm font-medium">
                                                            Create First Rank
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="p-4">
                                        {{ $positionRanks->links() }}
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
                <div class="bg-green-600 px-6 py-4 rounded-t-lg">
                    <h3 class="text-xl font-bold text-white">
                        {{ $editMode ? 'Edit' : 'Create' }} Position Rank
                    </h3>
                </div>
                <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                    <div class="p-6">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Rank Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="name" 
                                   id="name"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., Instructor I, Professor">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-800 font-medium">
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