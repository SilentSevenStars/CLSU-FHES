<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div class="relative p-6 w-full max-w-xl bg-gray-100 rounded-lg shadow-xl border border-gray-300">

        <h3 class="text-lg font-semibold mb-4 text-gray-800">Create Panel</h3>

        <form wire:submit.prevent="store">

            {{-- Name --}}
            <label class="block text-sm font-medium mb-1 text-gray-700">Name</label>
            <input type="text" wire:model="name"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]"
                placeholder="Enter full name">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Email --}}
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Email</label>
            <input type="email" wire:model="email"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]"
                placeholder="Enter email">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Password --}}
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Password</label>
            <input type="password" wire:model="password"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]"
                placeholder="Password">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Confirm --}}
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Confirm Password</label>
            <input type="password" wire:model="password_confirmation"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]"
                placeholder="Confirm password">

            {{-- Panel Position --}}
            <label class="block mt-4 text-sm font-medium mb-1 text-gray-700">Panel Position</label>
            <select wire:model.live="panel_position" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900
           focus:outline-none focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]">
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
                <option value="" selected>-- Select College --</option>
                @foreach($colleges as $col)
                <option value="{{ trim($col->name) }}">{{ $col->name }}</option>
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
                @elseif(!$college)
                <option value="">Please choose a college first</option>
                @else
                <option value="">-- Select Department --</option>
                @foreach($departments as $dep)
                <option value="{{ $dep->name }}">{{ $dep->name }}</option>
                @endforeach
                @endif
            </select>
            @error('department') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Debug info (temporary) --}}
            {{-- <div class="mt-2 text-xs text-gray-600">
                <div>Selected college value: <span class="font-medium">{{ $college ?? '(none)' }}</span></div>
                <div>Departments found: <span class="font-medium">{{ is_array($departments) ? count($departments) :
                        (method_exists($departments, 'count') ? $departments->count() : 0) }}</span></div>
            </div> --}}

            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" wire:click="$set('showCreateModal', false)"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-[#0D7A2F] hover:bg-[#0a6025] text-white rounded-lg font-medium transition">
                    Create
                </button>
            </div>

        </form>
    </div>
</div>