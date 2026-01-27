<!-- 
    FILE LOCATION: resources/views/livewire/nbc/partials/professional-dev-part1.blade.php
    This is PART 1 of the Professional Development Form
-->

<!-- Section 3.1 - Inventions and Publications (max 30 pts) -->
<div class="border-l-4 border-blue-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        3.1 Invention, patented inventions, innovations, publications and other creative works (maximum of 30 points)
    </h3>
    
    <div class="space-y-4">
        <!-- 3.1.1 -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.1.1 For every cost and time saving innovation, patented invention and creative work as well as discovery of an educational, technical scientific and/or cultural value
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input 
                        type="number" 
                        wire:model.live="rs_3_1_1"
                        step="0.01"
                        min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-center"
                    >
                </div>
            </div>
            @error('rs_3_1_1') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- 3.1.2 Published works -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">
                3.1.2 For every published: original, edited, or compiled, copyright/published within the last ten years
            </p>
            
            <div class="space-y-3 pl-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">a. as original author/s</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_1_2_a" max="5" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
                @error('rs_3_1_2_a') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">c. as reviewer</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_1_2_c" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
                @error('rs_3_1_2_c') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">d. as translator</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_1_2_d" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
                @error('rs_3_1_2_d') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">e. as editor</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_1_2_e" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
                @error('rs_3_1_2_e') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">f. as compiler</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_1_2_f" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
                @error('rs_3_1_2_f') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- 3.1.3 Research -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">
                3.1.3 For every scholarly research / monograph / educational technical articles in a technical/ scientific/professional journal
            </p>
            
            <div class="space-y-3 pl-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">a. International</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_1_3_a" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
                @error('rs_3_1_3_a') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">b. National</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_1_3_b" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
                @error('rs_3_1_3_b') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">c. Local</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_1_3_c" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
                @error('rs_3_1_3_c') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- 3.1.4 -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.1.4 For every instructional manual/audio-visual material
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input 
                        type="number" 
                        wire:model.live="rs_3_1_4"
                        step="0.01"
                        min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center"
                    >
                </div>
            </div>
            @error('rs_3_1_4') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <!-- Subtotal 3.1 -->
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-10">
                <label class="block text-base font-bold text-gray-900">
                    Section 3.1 Subtotal (MAX 20)
                </label>
            </div>
            <div class="col-span-2">
                <div class="px-3 py-2 bg-blue-100 border border-blue-300 rounded-lg text-center font-semibold">
                    {{ number_format($professionalDevelopment->subtotal31, 3) }}
                </div>
            </div>
        </div>
    </div>
</div>