<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-4xl font-extrabold text-[#1E7F3E] mb-2">Performance Scores</h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1E7F3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Manually add performance scores for Instructor III and above applicants
                </p>
            </div>

            {{-- Flash success --}}
            @if(session()->has('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Main Card --}}
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">

                {{-- Card header --}}
                <div class="bg-[#1E7F3E] p-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <i class="fa-solid fa-star-half-stroke text-white text-lg"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Applicant List</h2>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="px-6 py-4 border-b border-gray-300 flex flex-wrap items-end gap-3">

                    {{-- Search --}}
                    <div class="flex-1 min-w-[200px] max-w-xs">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Search Applicant</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model.live.debounce.400ms="searchTerm"
                                class="py-2 px-3 ps-10 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]"
                                placeholder="Name..."
                            />
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Position --}}
                    <div class="min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Position</label>
                        <select wire:model.live="selectedPosition"
                                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]">
                            <option value="">All Positions</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos }}">{{ $pos }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- College --}}
                    <div class="min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1">College</label>
                        <select wire:model.live="selectedCollege"
                                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]">
                            <option value="">All Colleges</option>
                            @foreach($colleges as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Department --}}
                    <div class="min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Department</label>
                        <select wire:model.live="selectedDepartment"
                                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]">
                            <option value="">All Departments</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Interview Date --}}
                    <div class="min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Interview Date</label>
                        <select wire:model.live="selectedDate"
                                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]">
                            <option value="">All Dates</option>
                            @foreach($interviewDates as $date)
                                <option value="{{ $date }}">{{ date('M d, Y', strtotime($date)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="min-w-[150px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select wire:model.live="selectedStatus"
                                class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#1E7F3E] focus:ring-[#1E7F3E]">
                            <option value="">All Status</option>
                            <option value="not yet">Not Yet</option>
                            <option value="complete">Complete</option>
                        </select>
                    </div>

                </div>

                {{-- Table --}}
                <div class="px-4 py-8 sm:px-6 lg:px-8">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="bg-gray-50 border border-gray-300 rounded-xl overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-black">Applicant</th>
                                            <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-black">Position</th>
                                            <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-black">College</th>
                                            <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-black">Department</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-black">Interview Date</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-black">Performance Score(s)</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-black">Status</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-black">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-gray-50">
                                        @forelse($applicants as $row)
                                            <tr class="hover:bg-gray-100 transition-colors">
                                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                                    {{ $row['name'] }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                                    {{ $row['position'] }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                                    {{ $row['college'] }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                                    {{ $row['department'] }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700 text-center whitespace-nowrap">
                                                    {{ date('M d, Y', strtotime($row['interview_date'])) }}
                                                </td>

                                                {{-- Performance score column --}}
                                                <td class="px-6 py-4 text-center">
                                                    @if($row['has_performance'])
                                                        <div class="flex flex-col items-center gap-1">
                                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-bold">
                                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Avg: {{ $row['avg_performance'] }}
                                                            </span>
                                                            @foreach($row['scored_by'] as $sb)
                                                                <span class="text-xs text-gray-500">
                                                                    {{ $sb['user_name'] }}: <span class="font-medium text-gray-700">{{ $sb['total_score'] }}</span>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs">
                                                            No score yet
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- Status column --}}
                                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                                    @if($row['status'] === 'complete')
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Complete
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Not Yet
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- Action --}}
                                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                                    @if($row['admin_already_scored'])
                                                        <span class="inline-flex items-center gap-1 text-xs text-gray-400 italic">
                                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Score submitted
                                                        </span>
                                                    @else
                                                        <button
                                                            wire:click="openModal({{ $row['evaluation_id'] }})"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-[#1E7F3E] hover:bg-[#156B2D] rounded-lg transition-colors focus:ring-2 focus:ring-green-300"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                            </svg>
                                                            Add Score
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-16 text-gray-500">
                                                    <div class="flex flex-col items-center gap-3">
                                                        <svg class="w-14 h-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        <p class="text-lg font-medium text-gray-500">No applicants found</p>
                                                        <p class="text-sm text-gray-400">Try adjusting the filters above</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if(count($applicants) > 0)
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between text-sm text-gray-500">
                        <span>
                            Showing <span class="font-semibold text-gray-700">{{ count($applicants) }}</span>
                            applicant{{ count($applicants) !== 1 ? 's' : '' }}
                        </span>
                        <span class="text-xs">* Instructor I and II are excluded — Instructor III and above only</span>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ── Score-Entry Modal ─────────────────────────────────────────────────── --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Panel --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 z-10 overflow-hidden">

                {{-- Modal header --}}
                <div class="bg-[#1E7F3E] px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 rounded-lg p-1.5">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">Add Performance Score</h3>
                    </div>
                    <button wire:click="closeModal" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal body --}}
                <div class="px-6 py-6 space-y-5">

                    {{-- Applicant info --}}
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Applicant</p>
                        <p class="text-base font-bold text-gray-900">{{ $modalApplicant }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $modalPosition }}</p>
                    </div>

                    {{-- Error --}}
                    @if($modalError)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg flex items-start gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-red-700">{{ $modalError }}</p>
                        </div>
                    @endif

                    {{-- Score input --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Performance Score <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            wire:model="scoreInput"
                            min="0"
                            max="30"
                            step="1"
                            class="block w-full px-4 py-3 text-2xl font-bold text-center border-2 border-gray-300 rounded-xl focus:border-[#1E7F3E] focus:ring-[#1E7F3E] transition-colors"
                            placeholder="0 – 30"
                            autofocus
                        />
                        <p class="text-xs text-gray-400 mt-1.5 text-center">Integer value between 0 and 30</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 pt-1">
                        <button
                            wire:click="closeModal"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            wire:click="saveScore"
                            wire:loading.attr="disabled"
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-[#1E7F3E] hover:bg-[#156B2D] rounded-lg transition-colors disabled:opacity-60 disabled:cursor-not-allowed focus:ring-2 focus:ring-green-300"
                        >
                            <span wire:loading.remove wire:target="saveScore">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Score
                            </span>
                            <span wire:loading wire:target="saveScore" style="display:none;">
                                <svg class="animate-spin w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>