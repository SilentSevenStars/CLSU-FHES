<div x-data="{ showDeleteModal: @entangle('showDeleteModal') }"
    class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
    <div class="max-w-7xl mx-auto">

        {{-- Flash messages --}}
        @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg shadow animate-fadeIn">
            {{ session('success') }}
        </div>
        @endif

        {{-- Header --}}
        <div class="mb-8 animate-fadeIn">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-[#0A6025] mb-2">
                        SPMS — Individual Performance Review
                    </h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Fill in your performance targets and actual accomplishments
                    </p>
                </div>

                {{-- Status badge --}}
                @if($ipr)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                    @if($ipr->status === 'submitted') bg-blue-100 text-blue-800
                    @elseif($ipr->status === 'approved') bg-green-100 text-green-800
                    @elseif($ipr->status === 'reviewed') bg-purple-100 text-purple-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($ipr->status) }}
                </span>
                @endif
            </div>
        </div>

        {{-- Evaluation Period Card --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-l-4 border-[#0A6025] animate-fadeIn">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Evaluation Period
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period Description</label>
                    <input type="text" wire:model="evaluation_period"
                        placeholder="e.g. January 1 to June 30, 2026"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]" />
                    @error('evaluation_period')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" wire:model="period_start"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]" />
                    @error('period_start')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" wire:model="period_end"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025]" />
                    @error('period_end')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Section Tabs --}}
        <div class="bg-white rounded-xl shadow-lg mb-6 border-l-4 border-[#0A6025] animate-fadeIn overflow-hidden">

            {{-- Tab Header --}}
            <div class="flex border-b border-gray-200">
                @foreach([
                    'A' => ['label' => 'A. Strategic Priorities', 'weight' => '40%', 'color' => 'blue'],
                    'B' => ['label' => 'B. Core Functions',       'weight' => '40%', 'color' => 'green'],
                    'C' => ['label' => 'C. Support Functions',    'weight' => '20%', 'color' => 'yellow'],
                ] as $tab => $meta)
                <button wire:click="$set('activeTab', '{{ $tab }}')"
                    class="flex-1 py-4 px-4 text-sm font-semibold transition-colors duration-200
                    {{ $activeTab === $tab
                        ? 'border-b-2 border-[#0A6025] text-[#0A6025] bg-green-50'
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                    {{ $meta['label'] }}
                    <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $activeTab === $tab ? 'bg-[#0A6025] text-white' : 'bg-gray-100 text-gray-600' }}">
                        {{ $meta['weight'] }}
                    </span>
                </button>
                @endforeach
            </div>

            {{-- Tab Content --}}
            <div class="p-6">

                {{-- Legend --}}
                <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 flex flex-wrap gap-4">
                    <span>¹ Q = Quality</span>
                    <span>² E = Efficiency</span>
                    <span>³ T = Timeliness</span>
                    <span>⁴ A = Average (Q + E + T) ÷ 3 — <strong>computed automatically by supervisor</strong></span>
                    <span class="ml-auto font-semibold">You fill: Columns A, B & C only</span>
                </div>

                @foreach(['A' => $strategic, 'B' => $core, 'C' => $support] as $section => $rows)
                @if($activeTab === $section)

                {{-- Table --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-[#0A6025] text-white">
                                <th class="px-4 py-3 text-left font-semibold w-1/5">Output (A)</th>
                                <th class="px-4 py-3 text-left font-semibold w-1/4">Success Indicators — Targets + Measures (B)</th>
                                <th class="px-4 py-3 text-left font-semibold w-1/4">Actual Accomplishments (C)</th>
                                <th class="px-4 py-3 text-center font-semibold w-10">Q¹</th>
                                <th class="px-4 py-3 text-center font-semibold w-10">E²</th>
                                <th class="px-4 py-3 text-center font-semibold w-10">T³</th>
                                <th class="px-4 py-3 text-center font-semibold w-12">A⁴</th>
                                <th class="px-4 py-3 text-center font-semibold w-10">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($rows as $idx => $row)
                            @php
                                $dbEntry = $ipr ? $ipr->entries->firstWhere('id', $row['db_id']) : null;
                                $avg = $dbEntry?->average;
                                $isRated = $dbEntry?->isRated();
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors {{ $idx % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }}">

                                {{-- Output --}}
                                <td class="px-3 py-2 align-top">
                                    <textarea
                                        wire:model="{{ strtolower(match($section) { 'A' => 'strategic', 'B' => 'core', 'C' => 'support' }) }}.{{ $idx }}.output"
                                        rows="3"
                                        placeholder="Describe the output/deliverable..."
                                        class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-md resize-none focus:outline-none focus:ring-1 focus:ring-[#0A6025] focus:border-[#0A6025]"></textarea>
                                    @error("{{ strtolower(match($section) { 'A' => 'strategic', 'B' => 'core', 'C' => 'support' }) }}.{{ $idx }}.output")
                                        <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                    @enderror
                                </td>

                                {{-- Success Indicators --}}
                                <td class="px-3 py-2 align-top">
                                    <textarea
                                        wire:model="{{ strtolower(match($section) { 'A' => 'strategic', 'B' => 'core', 'C' => 'support' }) }}.{{ $idx }}.success_indicators"
                                        rows="3"
                                        placeholder="Targets and measures..."
                                        class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-md resize-none focus:outline-none focus:ring-1 focus:ring-[#0A6025] focus:border-[#0A6025]"></textarea>
                                    @error("{{ strtolower(match($section) { 'A' => 'strategic', 'B' => 'core', 'C' => 'support' }) }}.{{ $idx }}.success_indicators")
                                        <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                    @enderror
                                </td>

                                {{-- Actual Accomplishments --}}
                                <td class="px-3 py-2 align-top">
                                    <textarea
                                        wire:model="{{ strtolower(match($section) { 'A' => 'strategic', 'B' => 'core', 'C' => 'support' }) }}.{{ $idx }}.actual_accomplishments"
                                        rows="3"
                                        placeholder="What was actually done..."
                                        class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-md resize-none focus:outline-none focus:ring-1 focus:ring-[#0A6025] focus:border-[#0A6025]"></textarea>
                                    @error("{{ strtolower(match($section) { 'A' => 'strategic', 'B' => 'core', 'C' => 'support' }) }}.{{ $idx }}.actual_accomplishments")
                                        <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                    @enderror
                                </td>

                                {{-- Q, E, T — read-only, filled by supervisor --}}
                                <td class="px-2 py-2 text-center align-middle">
                                    @if($isRated)
                                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                            {{ number_format($dbEntry->quality, 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 text-center align-middle">
                                    @if($isRated)
                                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                            {{ number_format($dbEntry->efficiency, 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 text-center align-middle">
                                    @if($isRated)
                                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                            {{ number_format($dbEntry->timeliness, 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- A⁴ Average — auto computed --}}
                                <td class="px-2 py-2 text-center align-middle">
                                    @if(!is_null($avg))
                                        <span class="inline-block px-2 py-1 rounded text-xs font-bold
                                            @if($avg >= 4.8) bg-green-100 text-green-800
                                            @elseif($avg >= 3.8) bg-blue-100 text-blue-800
                                            @elseif($avg >= 2.8) bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ number_format($avg, 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs italic">auto</span>
                                    @endif
                                </td>

                                {{-- Delete row --}}
                                <td class="px-2 py-2 text-center align-middle">
                                    @if(count($rows) > 1)
                                    <button wire:click="confirmDelete('{{ $section }}-{{ $idx }}')"
                                        class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                        {{-- Sub-total row --}}
                        <tfoot>
                            <tr class="bg-gray-100 border-t-2 border-gray-300">
                                <td colspan="6" class="px-4 py-2 text-right text-sm font-semibold text-gray-700">
                                    Sub-total for
                                    @if($section === 'A') A. Strategic Priorities (40%)
                                    @elseif($section === 'B') B. Core Functions (40%)
                                    @else C. Support Functions (20%) @endif :
                                </td>
                                <td class="px-2 py-2 text-center">
                                    @php $sub = $this->getSectionSubtotal($section); @endphp
                                    @if(!is_null($sub))
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-bold
                                            @if($sub >= 4.8) bg-green-100 text-green-800
                                            @elseif($sub >= 3.8) bg-blue-100 text-blue-800
                                            @elseif($sub >= 2.8) bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ number_format($sub, 3) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Add row button --}}
                <button wire:click="addRow('{{ $section }}')"
                    class="mt-3 flex items-center gap-2 px-4 py-2 text-sm font-medium text-[#0A6025] border border-[#0A6025] rounded-lg hover:bg-green-50 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add row
                </button>

                @endif
                @endforeach
            </div>
        </div>

        {{-- Summary Card (visible after saving) --}}
        @if($ipr && $ipr->final_weighted_rating)
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-l-4 border-[#0A6025] animate-fadeIn">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Individual Performance Rating Summary</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Category</th>
                            <th class="px-4 py-2 text-center font-semibold text-gray-700">Weight</th>
                            <th class="px-4 py-2 text-center font-semibold text-gray-700">Average</th>
                            <th class="px-4 py-2 text-center font-semibold text-gray-700">Weighted Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2 text-gray-700">A. Strategic Priorities</td>
                            <td class="px-4 py-2 text-center text-gray-600">40%</td>
                            <td class="px-4 py-2 text-center font-medium">{{ $ipr->strategic_avg ? number_format($ipr->strategic_avg, 3) : '—' }}</td>
                            <td class="px-4 py-2 text-center font-medium">{{ $ipr->strategic_avg ? number_format($ipr->strategic_avg * 0.40, 3) : '—' }}</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-2 text-gray-700">B. Core Functions</td>
                            <td class="px-4 py-2 text-center text-gray-600">40%</td>
                            <td class="px-4 py-2 text-center font-medium">{{ $ipr->core_avg ? number_format($ipr->core_avg, 3) : '—' }}</td>
                            <td class="px-4 py-2 text-center font-medium">{{ $ipr->core_avg ? number_format($ipr->core_avg * 0.40, 3) : '—' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-gray-700">C. Support Functions</td>
                            <td class="px-4 py-2 text-center text-gray-600">20%</td>
                            <td class="px-4 py-2 text-center font-medium">{{ $ipr->support_avg ? number_format($ipr->support_avg, 3) : '—' }}</td>
                            <td class="px-4 py-2 text-center font-medium">{{ $ipr->support_avg ? number_format($ipr->support_avg * 0.20, 3) : '—' }}</td>
                        </tr>
                        <tr class="bg-gray-100 font-semibold">
                            <td class="px-4 py-2" colspan="2">Total Overall Rating</td>
                            <td class="px-4 py-2 text-center" colspan="2">100%</td>
                        </tr>
                        <tr class="bg-green-50">
                            <td class="px-4 py-2 font-bold text-[#0A6025]" colspan="2">Final Weighted Rating</td>
                            <td class="px-4 py-2 text-center font-bold text-[#0A6025] text-lg" colspan="2">
                                {{ number_format($ipr->final_weighted_rating, 3) }}
                            </td>
                        </tr>
                        <tr class="bg-green-50">
                            <td class="px-4 py-2 font-bold text-[#0A6025]" colspan="2">Adjectival Rating</td>
                            <td class="px-4 py-2 text-center font-bold text-[#0A6025] text-lg" colspan="2">
                                {{ $ipr->adjectival_rating }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Rating Scale --}}
            <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-xs font-semibold text-gray-600 mb-2">Rating Scale:</p>
                <div class="flex flex-wrap gap-3 text-xs">
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded font-medium">4.80–5.00 Outstanding</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded font-medium">3.80–4.79 Very Satisfactory</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded font-medium">2.80–3.79 Satisfactory</span>
                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded font-medium">1.80–2.79 Unsatisfactory</span>
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded font-medium">1.00–1.79 Poor</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pb-8">
            @if(!$ipr || $ipr->status === 'draft')
            <button wire:click="saveDraft"
                wire:loading.attr="disabled"
                class="px-6 py-2.5 text-sm font-semibold text-[#0A6025] border-2 border-[#0A6025] rounded-lg hover:bg-green-50 transition-all duration-200 flex items-center gap-2 disabled:opacity-50">
                <svg wire:loading wire:target="saveDraft" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Save Draft
            </button>
            <button wire:click="submit"
                wire:loading.attr="disabled"
                class="px-6 py-2.5 text-sm font-semibold text-white bg-[#0A6025] rounded-lg hover:bg-[#0B712C] transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2 disabled:opacity-50">
                <svg wire:loading wire:target="submit" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Submit IPR
            </button>
            @elseif($ipr->status === 'submitted')
            <div class="flex items-center gap-2 px-4 py-2.5 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Submitted — awaiting supervisor review
            </div>
            @endif
        </div>

    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="$wire.cancelDelete()"></div>
            <div class="relative bg-white rounded-xl shadow-xl p-6 max-w-sm w-full z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Remove Row</h3>
                </div>
                <p class="text-gray-600 text-sm mb-6">Are you sure you want to remove this entry? This action cannot be undone.</p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="removeRow"
                        class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                        Remove
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
</style>