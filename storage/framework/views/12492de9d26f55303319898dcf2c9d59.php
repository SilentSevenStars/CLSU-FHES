<!-- 
    FILE LOCATION: resources/views/livewire/nbc/partials/professional-dev-part2.blade.php
    This is PART 2 of the Professional Development Form
-->

<!-- Section 3.2.1 - Training and Seminars (max 10 pts) -->
<div class="border-l-4 border-green-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        3.2 For expert services, training and active participation in professional/technical activities (maximum of 30 pts.)
    </h3>
    
    <h4 class="text-base font-semibold text-gray-800 mb-3">
        3.2.1 Training and Seminars (maximum of 10 pts.)
    </h4>

    <div class="space-y-4">
        <!-- 3.2.1.1 Training courses -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">
                3.2.1.1 For every training course with a duration of at least one year (pro-related or less than a year in accordance with the formula P= [(No. of Days)] / 251*] x full credit and not to exceed the full credit)
            </p>
            
            <div class="space-y-3 pl-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">a. International</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_2_1_1_a" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-center">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">b. National</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_2_1_1_b" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">c. Local</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_2_1_1_c" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3.2.1.2 Certified field training -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.2.1.2 For certified relevant field training (maximum of 5 pts.)
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_2_1_2" step="0.01" min="0" max="5"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>

        <!-- 3.2.1.3 Conferences, seminars, workshops -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">
                3.2.1.3 For participation in conferences, seminars, workshops
            </p>
            
            <div class="space-y-3 pl-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">a. International</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_2_1_3_a" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">b. National</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_2_1_3_b" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">c. Local</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_2_1_3_c" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subtotal 3.2.1 -->
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-10">
                <label class="block text-base font-bold text-gray-900">
                    Section 3.2.1 Subtotal (MAX 10)
                </label>
            </div>
            <div class="col-span-2">
                <div class="px-3 py-2 bg-green-100 border border-green-300 rounded-lg text-center font-semibold">
                    <?php echo e(number_format($this->subtotal321, 2)); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 3.2.2 - Expert Services Rendered (max 20 pts) -->
<div class="border-l-4 border-orange-500 pl-4 mb-6">
    <h4 class="text-base font-semibold text-gray-800 mb-3">
        3.2.2 Expert Services Rendered (maximum of 20 pts.)
    </h4>

    <div class="space-y-4">
        <!-- 3.2.2.1 Consultant/expert -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">
                3.2.2.1 For serving as a short-term consultant/expert in an activity of an educational, technological, professional scientific or culture nature (foreign or local) sponsored by the government or other agencies
            </p>
            
            <div class="space-y-3 pl-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">a. International</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_2_2_1_a" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">b. National</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_2_2_1_b" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-10">
                        <label class="block text-sm text-gray-600">c. Local</label>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="rs_3_2_2_1_c" step="0.01" min="0"
                            class="w-full px-3 py-2 border rounded-lg text-center">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3.2.2.2 Expert services rendered -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.2.2.2 For services rendered as coordinator/ lecturer/ resource person or guest speaker at conferences, workshops, and/or training programs
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_2_2_2" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>

        <!-- 3.2.2.3 Member of board -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.2.2.3 For expert services as member of the Board of Examiners in the Professional Regulations Commission or member of the Board of Accreditors of CHED and other similar groups
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_2_2_3" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>

        <!-- 3.2.2.4 Accreditation -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.2.2.4 For expert services in accreditation team and assistance work or member of the Board of Director, Accreditor, member of the Technical Committee or Chairman of a Group in Accreditation
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_2_2_4" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>

        <!-- 3.2.2.5 Testing officer -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.2.2.5 For expert services as testing officer
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_2_2_5" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>

        <!-- 3.2.2.6 Certification -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.2.2.6 For expert services as certification
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_2_2_6" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>

        <!-- 3.2.2.7 Coach/trainer -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.2.2.7 For services as coach / trainer of students in official exercise centers or with honors and distinction obtained per activity (maximum 1 point per year)
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-10">
                    <label class="block text-sm text-gray-600">RS Score</label>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="rs_3_2_2_7" step="0.01" min="0"
                        class="w-full px-3 py-2 border rounded-lg text-center">
                </div>
            </div>
        </div>
    </div>

    <!-- Subtotal 3.2.2 -->
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-10">
                <label class="block text-base font-bold text-gray-900">
                    Section 3.2.2 Subtotal (MAX 20)
                </label>
            </div>
            <div class="col-span-2">
                <div class="px-3 py-2 bg-orange-100 border border-orange-300 rounded-lg text-center font-semibold">
                    <?php echo e(number_format($this->subtotal322, 2)); ?>

                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\livewire\nbc\partials\professional-dev-part2.blade.php ENDPATH**/ ?>