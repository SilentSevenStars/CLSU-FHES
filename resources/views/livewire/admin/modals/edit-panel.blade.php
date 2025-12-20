<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div class="relative p-6 w-full max-w-xl bg-gray-100 rounded-lg shadow-xl border border-gray-300">

        <h2 class="text-lg font-semibold mb-4 text-[#0a6025]">Edit Panel</h2>

        <form wire:submit.prevent="update">

            {{-- Name --}}
            <label class="block text-sm font-medium mb-1 text-gray-700">Name</label>
            <input type="text" wire:model="name"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0a6025] focus:border-gray-400"
                placeholder="Enter full name">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Email --}}
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Email</label>
            <input type="email" wire:model="email" disabled
                class="w-full px-4 py-2 bg-gray-200 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
            <span class="text-xs text-gray-600">Email cannot be changed.</span>

            {{-- Panel Position --}}
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Panel Position</label>
            <select wire:model="panel_position" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900
           focus:outline-none focus:ring-2 focus:ring-[#0a6025] focus:border-gray-400">
                <option value="">-- Select Position --</option>
                <option value="Head">Head</option>
                <option value="Dean">Dean</option>
                <option value="Senior">Senior</option>
            </select>
            @error('panel_position') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- College --}}
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">College</label>
            <select wire:model="college" wire:change="setCollege($event.target.value)"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400">
                <option value="">-- Select College --</option>
                @foreach($colleges as $col)
                <option value="{{ $col->name }}">{{ $col->name }}</option>
                @endforeach
            </select>
            @error('college') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Department --}}
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Department</label>
            <select wire:model="department" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900
           focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400" @if($panel_position==='Dean' )
                disabled @endif>

                @if($panel_position === 'Dean')
                <option value="none">None</option>
                @elseif(empty($college))
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
            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" wire:click="$set('showEditModal', false)"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-[#0D7A2F] hover:bg-[#0A6025] text-white rounded-lg font-medium transition">
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>