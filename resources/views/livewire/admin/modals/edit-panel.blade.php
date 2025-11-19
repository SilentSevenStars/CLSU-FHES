<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div class="relative p-6 w-full max-w-xl bg-white rounded-lg shadow">

        <h3 class="text-lg font-semibold mb-4">Edit Panel</h3>

        <form wire:submit.prevent="update">

            {{-- Name --}}
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" wire:model="name" class="input w-full" placeholder="Enter full name">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Email --}}
            <label class="block mt-4 text-sm font-medium mb-1">Email</label>
            <input type="email" wire:model="email" disabled
                class="input w-full bg-gray-100 text-gray-500 cursor-not-allowed">
            <span class="text-xs text-gray-500">Email cannot be changed.</span>

            {{-- Panel Position --}}
            <label class="block mt-4 text-sm font-medium mb-1">Panel Position</label>
            <input type="text" wire:model="panel_position" class="input w-full" placeholder="Panel Position">
            @error('panel_position') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- College --}}
            <label class="block mt-4 text-sm font-medium mb-1">College</label>
            <select wire:model="college" wire:change="setCollege($event.target.value)" class="input w-full">
                <option value="">-- Select College --</option>
                @foreach($colleges as $col)
                    <option value="{{ $col->name }}">{{ $col->name }}</option>
                @endforeach
            </select>
            @error('college') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Department --}}
            <label class="block mt-4 text-sm font-medium mb-1">Department</label>

            <select wire:model="department" class="input w-full">
                @if(empty($college))
                    <option value="">Please choose a college first</option>
                @else
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $dep)
                        <option value="{{ $dep->name }}">{{ $dep->name }}</option>
                    @endforeach
                @endif
            </select>
            @error('department') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <!-- Buttons -->
            <div class="flex justify-end mt-6 space-x-2">
                <button type="button" wire:click="$set('showEditModal', false)" class="button-cancel">
                    Cancel
                </button>

                <button type="submit" class="button-primary">
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>
