<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div class="relative p-6 w-full max-w-xl bg-white rounded-lg shadow">

        <h3 class="text-lg font-semibold mb-4">Create Panel</h3>

        <form wire:submit.prevent="store">

            {{-- Name --}}
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" wire:model="name" class="input" placeholder="Enter full name">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Email --}}
            <label class="block mt-4 text-sm font-medium mb-1">Email</label>
            <input type="email" wire:model="email" class="input" placeholder="Enter email">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Password --}}
            <label class="block mt-4 text-sm font-medium mb-1">Password</label>
            <input type="password" wire:model="password" class="input" placeholder="Password">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Confirm --}}
            <label class="block mt-4 text-sm font-medium mb-1">Confirm Password</label>
            <input type="password" wire:model="password_confirmation" class="input" placeholder="Confirm password">

            {{-- Panel Position --}}
            <label class="block mt-4 text-sm font-medium mb-1">Panel Position</label>
            <input type="text" wire:model="panel_position" class="input" placeholder="Panel Position">
            @error('panel_position') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- College --}}
            <label class="block mt-4 text-sm font-medium mb-1">College</label>
            <select wire:model="college" wire:change="setCollege($event.target.value)" class="input">
                <option value="" selected>-- Select College --</option>
                @foreach($colleges as $col)
                <option value="{{ trim($col->name) }}">{{ $col->name }}</option>
                @endforeach
            </select>
            @error('college') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Department --}}
            <label class="block mt-4 text-sm font-medium mb-1">Department</label>
            <select wire:model="department" class="input">
                @if(!$college)
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
            <div class="mt-2 text-xs text-gray-500">
                <div>Selected college value: <span class="font-medium">{{ $college ?? '(none)' }}</span></div>
                <div>Departments found: <span class="font-medium">{{ is_array($departments) ? count($departments) : (method_exists($departments, 'count') ? $departments->count() : 0) }}</span></div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" wire:click="$set('showCreateModal', false)" class="button-cancel">Cancel</button>
                <button type="submit" class="button-primary">Create</button>
            </div>

        </form>
    </div>
</div>