{{-- Page 2: Section 3.2 - Activities (Training & Expert Services) --}}

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

{{-- 3.2.1 Training --}}
<div class="border-l-4 border-green-500 pl-4 mb-6">
    <h4 class="text-base font-semibold text-gray-800 mb-3">3.2.1 Training and Seminars (maximum of 10 pts.)</h4>

    <div class="space-y-4">
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">3.2.1.1 For every training course (at least 1 year, or pro-rated for shorter durations)</p>
            <div class="space-y-3 pl-4">
                @foreach([
                    ['new_q3_2_1_1_a','prev_q3_2_1_1_a','a. International'],
                    ['new_q3_2_1_1_b','prev_q3_2_1_1_b','b. National'],
                    ['new_q3_2_1_1_c','prev_q3_2_1_1_c','c. Local'],
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
            <p class="text-sm font-medium text-gray-700 mb-2">3.2.1.2 For certified relevant field training (maximum of 5 pts.)</p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-8"><label class="block text-sm text-gray-600">Score</label></div>
                <div class="col-span-2"><div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($prev_q3_2_1_2, 3) }}</div></div>
                <div class="col-span-2"><input type="number" wire:model.live="new_q3_2_1_2" step="0.001" min="0" max="5" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0"></div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">3.2.1.3 For participation in conferences, seminars, workshops</p>
            <div class="space-y-3 pl-4">
                @foreach([
                    ['new_q3_2_1_3_a','prev_q3_2_1_3_a','a. International'],
                    ['new_q3_2_1_3_b','prev_q3_2_1_3_b','b. National'],
                    ['new_q3_2_1_3_c','prev_q3_2_1_3_c','c. Local'],
                ] as [$nf,$pf,$label])
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-8"><label class="block text-sm text-gray-600">{{ $label }}</label></div>
                    <div class="col-span-2"><div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($$pf, 3) }}</div></div>
                    <div class="col-span-2"><input type="number" wire:model.live="{{ $nf }}" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-6">
                <label class="block text-base font-bold text-gray-900">Section 3.2.1 Subtotal</label>
                <p class="text-xs text-gray-500 mt-0.5">Total Points = Previous + New (capped at 10)</p>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-gray-600 mb-1">Total Previous Points</div>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center font-semibold text-gray-700">
                    {{ number_format($this->prevSubtotal321, 3) }}
                </div>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-green-700 mb-1">Total Points (capped at 10)</div>
                <div class="px-3 py-2 bg-green-100 border border-green-300 rounded-lg text-center font-bold text-green-900">
                    {{ number_format($this->subtotal321, 3) }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3.2.2 Expert Services --}}
<div class="border-l-4 border-orange-500 pl-4 mb-6">
    <h4 class="text-base font-semibold text-gray-800 mb-3">3.2.2 Expert Services Rendered (maximum of 20 pts.)</h4>

    <div class="space-y-4">
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-3">3.2.2.1 As short-term consultant/expert in educational, technological, professional, scientific, or cultural activities</p>
            <div class="space-y-3 pl-4">
                @foreach([
                    ['new_q3_2_2_1_a','prev_q3_2_2_1_a','a. International'],
                    ['new_q3_2_2_1_b','prev_q3_2_2_1_b','b. National'],
                    ['new_q3_2_2_1_c','prev_q3_2_2_1_c','c. Local'],
                ] as [$nf,$pf,$label])
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-8"><label class="block text-sm text-gray-600">{{ $label }}</label></div>
                    <div class="col-span-2"><div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($$pf, 3) }}</div></div>
                    <div class="col-span-2"><input type="number" wire:model.live="{{ $nf }}" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0"></div>
                </div>
                @endforeach
            </div>
        </div>

        @foreach([
            ['new_q3_2_2_2','prev_q3_2_2_2','3.2.2.2 As coordinator/lecturer/resource person/guest speaker at conferences, workshops, or training programs'],
            ['new_q3_2_2_3','prev_q3_2_2_3','3.2.2.3 As member of the Board of Examiners (PRC) or Board of Accreditors (CHED)'],
            ['new_q3_2_2_4','prev_q3_2_2_4','3.2.2.4 As member of accreditation team / Board of Directors / Technical Committee in Accreditation'],
            ['new_q3_2_2_5','prev_q3_2_2_5','3.2.2.5 As testing officer'],
            ['new_q3_2_2_6','prev_q3_2_2_6','3.2.2.6 For certification services'],
            ['new_q3_2_2_7','prev_q3_2_2_7','3.2.2.7 As coach/trainer of students with honors/distinction (max 1 pt/year)'],
        ] as [$nf,$pf,$label])
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-2">{{ $label }}</p>
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-8"><label class="block text-sm text-gray-600">Score</label></div>
                <div class="col-span-2"><div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-center font-semibold text-gray-700 text-sm">{{ number_format($$pf, 3) }}</div></div>
                <div class="col-span-2"><input type="number" wire:model.live="{{ $nf }}" step="0.001" min="0" class="w-full px-3 py-2 border rounded-lg text-center" placeholder="+0"></div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 pt-4 border-t border-gray-300">
        <div class="grid grid-cols-12 gap-4 items-center">
            <div class="col-span-6">
                <label class="block text-base font-bold text-gray-900">Section 3.2.2 Subtotal</label>
                <p class="text-xs text-gray-500 mt-0.5">Total Points = Previous + New (capped at 20)</p>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-gray-600 mb-1">Total Previous Points</div>
                <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-center font-semibold text-gray-700">
                    {{ number_format($this->prevSubtotal322, 3) }}
                </div>
            </div>
            <div class="col-span-3">
                <div class="text-xs text-center text-orange-700 mb-1">Total Points (capped at 20)</div>
                <div class="px-3 py-2 bg-orange-100 border border-orange-300 rounded-lg text-center font-bold text-orange-900">
                    {{ number_format($this->subtotal322, 3) }}
                </div>
            </div>
        </div>
    </div>
</div>