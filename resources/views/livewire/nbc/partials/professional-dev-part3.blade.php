{{-- Page 3: Sections 3.3–3.6 --}}

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

{{-- 3.3 Academic Distinctions --}}
<div class="border-l-4 border-indigo-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">3.3 Academic Distinctions (maximum of 10 points)</h3>

    <div class="space-y-4">
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">3.3.1 For earned latin honors from a SUC / CHED supervised institution</p>
            <div class="space-y-3 pl-4">
                @foreach([
                    ['new_q3_3_1_a','prev_q3_3_1_a','a. International'],
                    ['new_q3_3_1_b','prev_q3_3_1_b','b. National'],
                    ['new_q3_3_1_c','prev_q3_3_1_c','c. Local'],
                ] as [$nf,$pf,$label])
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-8"><label class="block text-sm text-gray-600">{{ $label }}</label></div>
                    <div class="col-span-2"><div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($$pf, 3) }}</div></div>
                    <div class="col-span-2"><input type="number" wire:model.live="{{ $nf }}" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0"></div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">3.3.2 Academic awards</p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-8"><label class="block text-sm text-gray-600">Score</label></div>
                <div class="col-span-2"><div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($prev_q3_3_2, 3) }}</div></div>
                <div class="col-span-2"><input type="number" wire:model.live="new_q3_3_2" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0"></div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">3.3.3 Scholarship/Fellowship (degree or non-degree granting)</p>
            <div class="space-y-4 pl-4">
                @foreach([
                    ['a. International competitive', [
                        ['new_q3_3_3_a_doctorate','prev_q3_3_3_a_doctorate','Doctorate'],
                        ['new_q3_3_3_a_masters','prev_q3_3_3_a_masters','Masters'],
                        ['new_q3_3_3_a_nondegree','prev_q3_3_3_a_nondegree','Non-Degree'],
                    ]],
                    ['b. International, non-competitive', [
                        ['new_q3_3_3_b_doctorate','prev_q3_3_3_b_doctorate','Doctorate'],
                        ['new_q3_3_3_b_masters','prev_q3_3_3_b_masters','Masters'],
                        ['new_q3_3_3_b_nondegree','prev_q3_3_3_b_nondegree','Non-Degree'],
                    ]],
                    ['c. National/Regional, competitive', [
                        ['new_q3_3_3_c_doctorate','prev_q3_3_3_c_doctorate','Doctorate'],
                        ['new_q3_3_3_c_masters','prev_q3_3_3_c_masters','Masters'],
                        ['new_q3_3_3_c_nondegree','prev_q3_3_3_c_nondegree','Non-Degree'],
                    ]],
                    ['d. National/Regional, non-competitive', [
                        ['new_q3_3_3_d_doctorate','prev_q3_3_3_d_doctorate','Doctorate'],
                        ['new_q3_3_3_d_masters','prev_q3_3_3_d_masters','Masters'],
                    ]],
                ] as [$groupLabel, $items])
                <div class="border-l-2 border-indigo-300 pl-3">
                    <p class="text-sm font-semibold text-gray-700 mb-2">{{ $groupLabel }}</p>
                    <div class="space-y-2">
                        @foreach($items as [$nf,$pf,$sublabel])
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-8"><label class="block text-xs text-gray-600">{{ $sublabel }}</label></div>
                            <div class="col-span-2"><div class="px-2 py-1 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-xs">{{ number_format($$pf, 3) }}</div></div>
                            <div class="col-span-2"><input type="number" wire:model.live="{{ $nf }}" step="0.001" min="0" class="w-full px-2 py-1 border rounded text-center text-sm" placeholder="+0"></div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="border-l-2 border-indigo-300 pl-3">
                    <p class="text-sm font-semibold text-gray-700 mb-2">e. Local, competitive or non-competitive</p>
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-8"><label class="block text-xs text-gray-600">Score</label></div>
                        <div class="col-span-2"><div class="px-2 py-1 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-xs">{{ number_format($prev_q3_3_3_e, 3) }}</div></div>
                        <div class="col-span-2"><input type="number" wire:model.live="new_q3_3_3_e" step="0.001" min="0" class="w-full px-2 py-1 border rounded text-center text-sm" placeholder="+0"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-6">
                <label class="block text-base font-bold text-gray-900">Section 3.3 Subtotal</label>
                <p class="text-xs text-gray-500 mt-0.5">Total Points = Previous + New (capped at 10)</p>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-gray-600 mb-1">Total Previous Points</div>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center font-semibold text-gray-700">
                    {{ number_format($this->prevSubtotal33, 3) }}
                </div>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-indigo-700 mb-1">Total Points (capped at 10)</div>
                <div class="px-3 py-2 bg-indigo-100 border border-indigo-300 rounded-lg text-center font-bold text-indigo-900">
                    {{ number_format($this->subtotal33, 3) }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3.4 Awards --}}
<div class="border-l-4 border-pink-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">3.4 Awards of distinction received in recognition of (maximum of 5 points)</h3>
    <div class="space-y-3">
        @foreach([
            ['new_q3_4_a','prev_q3_4_a','a. International competitive'],
            ['new_q3_4_b','prev_q3_4_b','b. National/Regional'],
            ['new_q3_4_c','prev_q3_4_c','c. Local'],
        ] as [$nf,$pf,$label])
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-8"><label class="block text-sm text-gray-600">{{ $label }}</label></div>
                <div class="col-span-2"><div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($$pf, 3) }}</div></div>
                <div class="col-span-2"><input type="number" wire:model.live="{{ $nf }}" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0"></div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-6">
                <label class="block text-base font-bold text-gray-900">Section 3.4 Subtotal</label>
                <p class="text-xs text-gray-500 mt-0.5">Total Points = Previous + New (capped at 5)</p>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-gray-600 mb-1">Total Previous Points</div>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center font-semibold text-gray-700">
                    {{ number_format($this->prevSubtotal34, 3) }}
                </div>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-pink-700 mb-1">Total Points (capped at 5)</div>
                <div class="px-3 py-2 bg-pink-100 border border-pink-300 rounded-lg text-center font-bold text-pink-900">
                    {{ number_format($this->subtotal34, 3) }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3.5 Community Outreach --}}
<div class="border-l-4 border-yellow-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">3.5 Community Outreach (maximum of 5 points)</h3>
    <div class="bg-gray-50 rounded-lg p-4">
        <p class="text-sm font-medium text-gray-700 mb-2">3.5.1 For every year of participation in service-oriented community projects</p>
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-8"><label class="block text-sm text-gray-600">Score (Q3_3_5_1)</label></div>
            <div class="col-span-2"><div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($prev_q3_3_5_1, 3) }}</div></div>
            <div class="col-span-2"><input type="number" wire:model.live="new_q3_3_5_1" step="0.001" min="0" max="5" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0"></div>
        </div>
        @error('new_q3_3_5_1') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-6">
                <label class="block text-base font-bold text-gray-900">Section 3.5 Subtotal</label>
                <p class="text-xs text-gray-500 mt-0.5">Total Points = Previous + New (capped at 5)</p>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-gray-600 mb-1">Total Previous Points</div>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center font-semibold text-gray-700">
                    {{ number_format($this->prevSubtotal35, 3) }}
                </div>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-yellow-700 mb-1">Total Points (capped at 5)</div>
                <div class="px-3 py-2 bg-yellow-100 border border-yellow-300 rounded-lg text-center font-bold text-yellow-900">
                    {{ number_format($this->subtotal35, 3) }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3.6 Professional Examinations --}}
<div class="border-l-4 border-red-500 pl-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">3.6 Professional Examinations (maximum of 10 points)</h3>
    <p class="text-sm text-gray-700 mb-4">3.6.1 For every relevant licensure and other professional examinations passed</p>
    <div class="space-y-3">
        @foreach([
            ['new_q3_6_1_a','prev_q3_6_1_a','a. Engineering, Accounting, Medicine, Law, Teacher\'s Board, etc.'],
            ['new_q3_6_1_b','prev_q3_6_1_b','b. Career Executive Service / Career Service Executive Examinations'],
            ['new_q3_6_1_c','prev_q3_6_1_c','c. Marine Board / Master Electrician / Professional Radio Operator / similar certificates'],
            ['new_q3_6_1_d','prev_q3_6_1_d','d. Other trade skill certificates'],
        ] as [$nf,$pf,$label])
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-8"><label class="block text-sm text-gray-600">{{ $label }}</label></div>
                <div class="col-span-2"><div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($$pf, 3) }}</div></div>
                <div class="col-span-2"><input type="number" wire:model.live="{{ $nf }}" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0"></div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-6">
                <label class="block text-base font-bold text-gray-900">Section 3.6 Subtotal</label>
                <p class="text-xs text-gray-500 mt-0.5">Total Points = Previous + New (capped at 10)</p>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-gray-600 mb-1">Total Previous Points</div>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center font-semibold text-gray-700">
                    {{ number_format($this->prevSubtotal36, 3) }}
                </div>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-red-700 mb-1">Total Points (capped at 10)</div>
                <div class="px-3 py-2 bg-red-100 border border-red-300 rounded-lg text-center font-bold text-red-900">
                    {{ number_format($this->subtotal36, 3) }}
                </div>
            </div>
        </div>
    </div>
</div>