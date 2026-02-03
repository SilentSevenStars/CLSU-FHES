<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0a6025] bg-clip-text text-transparent mb-2">
                            Create Position
                        </h1>
                        <p class="text-gray-600">Add a new position to the system</p>
                    </div>
                    <a href="{{ route('admin.position') }}" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-xl p-8">
                <form wire:submit.prevent="store">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Position Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Position Name <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Select Position Rank --</option>

                                @foreach ($positionRanks as $rank)
                                <option value="{{ $rank->name }}">
                                    {{ $rank->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- College (using college_id) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                College <span class="text-red-500">*</span>
                            </label>
                            {{-- wire:model.live triggers updatedCollegeId() --}}
                            <select wire:model.live="college_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Select College</option>
                                @foreach($colleges as $college)
                                {{-- Use college ID instead of name --}}
                                <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                            @error('college_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Department (using department_id, filtered by college) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="department_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                @if(!$college_id) disabled @endif>
                                <option value="">
                                    @if(!$college_id)
                                    Select a college first
                                    @else
                                    Select Department
                                    @endif
                                </option>
                                @foreach($departments as $dept)
                                {{-- Use department ID instead of name --}}
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="vacant">Vacant</option>
                                <option value="promotion">Promotion</option>
                                <option value="none">None</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Specialization -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Specialization <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="specialization"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('specialization') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Education -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Education <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                wire:model.defer="education"
                                list="educationOptions"
                                placeholder="e.g., Master of Science in Information Technology"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg
               focus:ring-2 focus:ring-green-500 focus:border-green-500">

                            <datalist id="educationOptions">
                                @foreach($educationOptions as $option)
                                <option value="{{ $option }}">
                                    @endforeach
                            </datalist>

                            @error('education')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Eligibility -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Eligibility <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="eligibility"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., Licensed Professional">
                            @error('eligibility') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Experience -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Experience (years) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="experience" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('experience') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Training -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Training (hours) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="training" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('training') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model="start_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                End Date
                            </label>
                            <input type="date" wire:model="end_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 mt-8">
                        <button type="button" wire:click="cancel"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition-colors">
                            Create Position
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>