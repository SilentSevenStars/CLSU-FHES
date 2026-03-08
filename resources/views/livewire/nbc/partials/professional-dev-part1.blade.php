{{-- Page 1: Section 3.1 - Creative Works --}}

<div class="border-l-4 border-blue-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        3.1 Invention, patented inventions, innovations, publications and other creative works (maximum of 20 points)
    </h3>

    {{-- Column Headers --}}
    <div class="grid grid-cols-12 gap-4 mb-3">
        <div class="col-span-8"></div>
        <div class="col-span-2 text-center">
            <span class="inline-block text-xs font-semibold uppercase tracking-wide text-gray-600 bg-gray-100 border border-gray-300 rounded px-2 py-1 w-full">Previous</span>
        </div>
        <div class="col-span-2 text-center">
            <span class="inline-block text-xs font-semibold uppercase tracking-wide text-green-700 bg-green-50 border border-green-200 rounded px-2 py-1 w-full">Add New</span>
        </div>
    </div>

    <div class="space-y-4">
        {{-- 3.1.1 --}}
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
                3.1.1 For every cost/time saving innovation, patented invention, creative work, or discovery of educational/scientific/cultural value
            </p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-8"><label class="block text-sm text-gray-600">RS Score (Q3_1_1)</label></div>
                <div class="col-span-2">
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-center font-semibold text-gray-700 text-sm">{{ number_format($prev_q3_1_1, 3) }}</div>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="new_q3_1_1" step="0.001" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-center" placeholder="+0">
                </div>
            </div>
            @error('new_q3_1_1') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- 3.1.2 Published works --}}
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">3.1.2 For every published: original, edited, or compiled work (copyright/published within last 10 years)</p>
            <div class="space-y-3 pl-4">
                @foreach([
                    ['new_q3_1_2_a', 'prev_q3_1_2_a', 'a. as original author/s'],
                    ['new_q3_1_2_c', 'prev_q3_1_2_c', 'c. as reviewer'],
                    ['new_q3_1_2_d', 'prev_q3_1_2_d', 'd. as translator'],
                    ['new_q3_1_2_e', 'prev_q3_1_2_e', 'e. as editor'],
                    ['new_q3_1_2_f', 'prev_q3_1_2_f', 'f. as compiler'],
                ] as [$newField, $prevField, $label])
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-8"><label class="block text-sm text-gray-600">{{ $label }}</label></div>
                    <div class="col-span-2">
                        <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($$prevField, 3) }}</div>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="{{ $newField }}" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 3.1.3 Research --}}
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">3.1.3 For every scholarly research / monograph / educational technical article in a scientific/professional journal</p>
            <div class="space-y-3 pl-4">
                @foreach([
                    ['new_q3_1_3_a', 'prev_q3_1_3_a', 'a. International'],
                    ['new_q3_1_3_b', 'prev_q3_1_3_b', 'b. National'],
                    ['new_q3_1_3_c', 'prev_q3_1_3_c', 'c. Local'],
                ] as [$newField, $prevField, $label])
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-8"><label class="block text-sm text-gray-600">{{ $label }}</label></div>
                    <div class="col-span-2">
                        <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($$prevField, 3) }}</div>
                    </div>
                    <div class="col-span-2">
                        <input type="number" wire:model.live="{{ $newField }}" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 3.1.4 --}}
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">3.1.4 For every instructional manual / audio-visual material</p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-8"><label class="block text-sm text-gray-600">RS Score (Q3_1_4)</label></div>
                <div class="col-span-2">
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-center font-semibold text-gray-700 text-sm">{{ number_format($prev_q3_1_4, 3) }}</div>
                </div>
                <div class="col-span-2">
                    <input type="number" wire:model.live="new_q3_1_4" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0">
                </div>
            </div>
        </div>
    </div>

    {{-- Subtotal 3.1 --}}
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-8">
                <label class="block text-base font-bold text-gray-900">Section 3.1 Subtotal (MAX 20)</label>
            </div>
            <div class="col-span-4">
                <div class="px-3 py-2 bg-blue-100 border border-blue-300 rounded-lg text-center font-bold text-blue-900">
                    {{ number_format($this->subtotal31, 3) }}
                </div>
            </div>
        </div>
    </div>
</div>