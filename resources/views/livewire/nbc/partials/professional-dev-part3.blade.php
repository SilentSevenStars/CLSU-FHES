<!-- 
    FILE LOCATION: resources/views/livewire/nbc/partials/professional-dev-part3.blade.php
    This is PART 3 of the Professional Development Form - COMPLETE VERSION
-->

<!-- Section 3.3 - Academic Distinctions (max 30 pts) -->
<div class="border-l-4 border-indigo-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        3.3 Academic Distinctions (maximum of 30 points)
    </h3>
    
    <div class="space-y-4">
        <!-- 3.3.1 Latin honors -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">
                3.3.1 For earned latin honors graduated from a SUC, CHED supervised institution
            </p>
            
            <div class="space-y-3 pl-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">a. International</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_3_1_a" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-center">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">b. National</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_3_1_b" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">c. Local</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_3_1_c" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3.3.2 Academic awards -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.3.2 Academic awards
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_3_2" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>

        <!-- 3.3.3 Scholarship/Fellowship -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">
                3.3.3 Scholarship/Fellowship. This may be degree or non-degree granting
            </p>
            
            <div class="space-y-4 pl-4">
                <!-- International competitive -->
                <div class="border-l-2 border-blue-300 pl-3">
                    <p class="text-sm font-semibold text-gray-700 mb-2">a. International competitive</p>
                    <div class="space-y-2">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Doctorate</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_a_doctorate" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Masters</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_a_masters" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Non-Degree</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_a_nondegree" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- International, non-competitive -->
                <div class="border-l-2 border-blue-300 pl-3">
                    <p class="text-sm font-semibold text-gray-700 mb-2">b. International, non-competitive</p>
                    <div class="space-y-2">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Doctorate</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_b_doctorate" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Masters</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_b_masters" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Non-Degree</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_b_nondegree" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- National/Regional, competitive -->
                <div class="border-l-2 border-blue-300 pl-3">
                    <p class="text-sm font-semibold text-gray-700 mb-2">c. National/Regional, competitive</p>
                    <div class="space-y-2">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Doctorate</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_c_doctorate" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Masters</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_c_masters" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Non-Degree</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_c_nondegree" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- National/Regional, non-competitive -->
                <div class="border-l-2 border-blue-300 pl-3">
                    <p class="text-sm font-semibold text-gray-700 mb-2">d. National/Regional, non-competitive</p>
                    <div class="space-y-2">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Doctorate</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_d_doctorate" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-10">
                                <label class="block text-xs text-gray-600">Masters</label>
                            </div>
                            <div class="col-span-2">
                                <input type="number" wire:model.live="rs_3_3_3_d_masters" step="0.01" min="0"
                                    class="w-full px-2 py-1 border rounded text-center text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Local, competitive or non-competitive -->
                <div class="border-l-2 border-blue-300 pl-3">
                    <p class="text-sm font-semibold text-gray-700 mb-2">e. Local, competitive or non-competitive</p>
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-10">
                            <label class="block text-xs text-gray-600">RS Score</label>
                        </div>
                        <div class="col-span-2">
                            <input type="number" wire:model.live="rs_3_3_3_e" step="0.01" min="0"
                                class="w-full px-2 py-1 border rounded text-center text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subtotal 3.3 -->
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-10">
                <label class="block text-base font-bold text-gray-900">
                    Section 3.3 Subtotal (MAX 30)
                </label>
            </div>
            <div class="col-span-2">
                <div class="px-3 py-2 bg-indigo-100 border border-indigo-300 rounded-lg text-center font-semibold">
                    {{ number_format($this->subtotal33, 2) }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 3.4 - Awards of Distinction -->
<div class="border-l-4 border-pink-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        3.4 Awards of distinction received in recognition of
    </h3>
    
    <div class="space-y-3">
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">a. International competitive</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_4_a" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">b. National/Regional</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_4_b" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">c. Local</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_4_c" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 3.5 - Community Outreach (max 5 pts) -->
<div class="border-l-4 border-yellow-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        3.5 Community Outreach (maximum of 5 points)
    </h3>
    
    <div class="bg-gray-50 rounded-lg p-4">
        <p class="text-sm font-medium text-gray-700 mb-2">
            3.5.1 For every year of participation in service-oriented projects in the community
        </p>
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-10">
                <label class="block text-sm text-gray-600">RS Score (Maximum 5 points)</label>
            </div>
            <div class="col-span-2">
                <input type="number" wire:model.live="rs_3_5_1" step="0.01" min="0" max="5"
                    class="w-full px-3 py-2 border rounded-lg text-center">
            </div>
        </div>
        @error('rs_3_5_1') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Subtotal 3.5 -->
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-10">
                <label class="block text-base font-bold text-gray-900">
                    Section 3.5 Subtotal (MAX 5)
                </label>
            </div>
            <div class="col-span-2">
                <div class="px-3 py-2 bg-yellow-100 border border-yellow-300 rounded-lg text-center font-semibold">
                    {{ number_format($this->subtotal35, 2) }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 3.6 - Professional Examinations (max 10 pts) -->
<div class="border-l-4 border-red-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        3.6 Professional Examinations (maximum of 10 points)
    </h3>
    
    <p class="text-sm text-gray-700 mb-4">
        3.6.1 For every relevant licensure and other professional examinations passed
    </p>

    <div class="space-y-3">
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">
                        a. Engineering, Accounting, Medicine, Law, Teacher's Board, etc.
                    </label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_6_1_a" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
            @error('rs_3_6_1_a') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">
                        b. Career Executive Service Officer's Examinations/ Career Service Executive Examinations
                    </label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_6_1_b" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
            @error('rs_3_6_1_b') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">
                        c. Marine Board/Seaman Certificate; Master Electrician/Master Plumber Certificate etc.; Plant Mechanic Certificate; Professional Radio Operator Certificate
                    </label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_6_1_c" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
            @error('rs_3_6_1_c') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">
                        d. Other trade skill Certificates
                    </label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_6_1_d" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
            @error('rs_3_6_1_d') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <!-- Subtotal 3.6 -->
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-10">
                <label class="block text-base font-bold text-gray-900">
                    Section 3.6 Subtotal (MAX 10)
                </label>
            </div>
            <div class="col-span-2">
                <div class="px-3 py-2 bg-red-100 border border-red-300 rounded-lg text-center font-semibold">
                    {{ number_format($this->subtotal36, 2) }}
                </div>
            </div>
        </div>
    </div>
</div>