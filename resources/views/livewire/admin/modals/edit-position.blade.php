<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div class="relative p-6 w-full max-w-2xl bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="flex justify-between items-center border-b pb-3 mb-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Position</h3>
            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">✕</button>
        </div>

        <form wire:submit.prevent="update">
            <div class="space-y-4 mb-4">
                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position
                        Name</label>
                    <input type="text" wire:model='name' id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Position Name" required />
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">College</label>
                    <select wire:model.live="college"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full">
                        <option value="">Select a College</option>
                        @foreach($colleges as $c)
                        <option value="{{ $c->name }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('college') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Department</label>

                    <select wire:model="department"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full"
                        @if(!$college) disabled @endif>

                        <option value="">
                            @if(!$college)
                            Select a college first
                            @else
                            Select a department
                            @endif
                        </option>

                        @if($college)
                        @foreach($departments as $d)
                        <option value="{{ $d->name }}">{{ $d->name }}</option>
                        @endforeach
                        @endif
                    </select>

                    @error('department') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                        Status</label>
                    <select id="countries" wire:model='status'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Choose a status</option>
                        <option value="vacant">Vacant</option>
                        <option value="promotion">Promotion</option>
                        <option value="none">None</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-6">
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
                        date</label>
                    <input type="date" wire:model="start_date" id="start_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required />
                    @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-6">
                    <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
                        date</label>
                    <input type="date" wire:model="end_date" id="end_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" wire:click="closeModal"
                    class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</button>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save
                    Changes</button>
            </div>
        </form>
    </div>
</div>