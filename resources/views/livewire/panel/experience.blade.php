<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold text-[#0A6025] mb-2">
                            Experience Evaluation
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Evaluate applicant's credentials and experiences
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn">
                <div class="bg-[#0A6025] p-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">II. Entry Credentials and Related Experiences</h2>
                    </div>
                </div>

                <div class="p-8">

            <form wire:submit.prevent="saveExperience">
                <div class="space-y-6">
                    <!-- Table Header -->
                    <div class="grid grid-cols-12 gap-4 pb-4 border-b-2 border-gray-300">
                        <div class="col-span-7"></div>
                        <div class="col-span-2 text-center">
                            <span class="font-bold text-lg">Maximum points</span>
                        </div>
                        <div class="col-span-3 text-center">
                            <span class="font-bold text-lg">Earned points</span>
                        </div>
                    </div>

                    <!-- 1. Educational Qualification -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">1. Educational Qualification (based on NCC Criteria)</span>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">85</span>
                        </div>
                        <div class="col-span-3">
                            <input type="number" 
                                   wire:model.live="education_qualification"
                                   min="0" 
                                   max="85"
                                   step="0.01"
                                   placeholder="Input here"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] transition-all">
                            @error('education_qualification') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 2. Academic/Administrative Experience -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">2. Academic/ Administrative and Industrial/Agricultural/Teaching</span>
                            <div class="text-sm text-gray-600 mt-2">
                                <div class="mb-2">
                                    <span class="font-medium">a. Applicant for College Level</span>
                                    <ul class="list-disc ml-6 mt-1">
                                        <li>Teaching Experience in College - 1 pt/yr</li>
                                        <li>Teaching Experience in High School - 1 pt/yr</li>
                                    </ul>
                                </div>
                                <div>
                                    <span class="font-medium">b. Applicant for HS Level</span>
                                    <ul class="list-disc ml-6 mt-1">
                                        <li>Teaching Experience in High School - 1 pt/yr</li>
                                        <li>Teaching Experience in College - 0.5 pt/yr</li>
                                        <li>Industrial/Agricultural/Research - 1 pt/yr</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">25</span>
                        </div>
                        <div class="col-span-3">
                            <input type="number" 
                                   wire:model.live="experience_type"
                                   min="0" 
                                   max="25"
                                   step="0.01"
                                   placeholder="Input here"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] transition-all">
                            @error('experience_type') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 3. Passing Licensure Examination -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">3. Passing appropriate Licensure Examination</span>
                            <div class="text-sm text-gray-600 mt-1">
                                National Certification (NC II) - 3
                            </div>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">5</span>
                        </div>
                        <div class="col-span-3">
                            <input type="number" 
                                   wire:model.live="licensure_examination"
                                   min="3"
                                   max="5"
                                   step="0.01"
                                   placeholder="Input here"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] transition-all">
                            @error('licensure_examination') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 4. Place in Board Examination -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">4. Place in Board Examination</span>
                            <div class="text-sm text-gray-600 mt-1">
                                • 1st Place - 10 pts<br>
                                • 2nd Place - 8 pts<br>
                                • 3rd to 20th Place - 5 pts
                            </div>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">10</span>
                        </div>
                        <div class="col-span-3">
                            <select wire:model.live="place_board_exam"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] appearance-none bg-white transition-all">
                                @foreach($placeBoardExamOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('place_board_exam') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 5. Professional Activities -->
                    <div class="grid grid-cols-12 gap-4 items-start py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">5. Participation in Professional activities such as seminar workshops and trainings</span>
                            <div class="text-sm text-gray-600 mt-1">
                                • 1 point for every 8 hours attendance
                            </div>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">15</span>
                        </div>
                        <div class="col-span-3">
                            <input type="number" 
                                   wire:model.live="professional_activities"
                                   min="0" 
                                   max="15"
                                   step="1"
                                   placeholder="Input here"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] transition-all">
                            @error('professional_activities') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 6. Academic Performance -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">6. Academic Performance</span>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">10</span>
                        </div>
                        <div class="col-span-3">
                            <select wire:model.live="academic_performance"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] appearance-none bg-white transition-all">
                                @foreach($academicPerformanceOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('academic_performance') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 7. Publications -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">7. Publications (Scopus indexed)</span>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">10</span>
                        </div>
                        <div class="col-span-3">
                            <select wire:model.live="publication"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] appearance-none bg-white transition-all">
                                @foreach($publicationOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('publication') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 8. School Graduated from -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">8. School Graduated from</span>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">15</span>
                        </div>
                        <div class="col-span-3">
                            <select wire:model.live="school_graduate"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0A6025] focus:border-[#0A6025] appearance-none bg-white transition-all">
                                @foreach($schoolGraduateOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('school_graduate') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Section Total Score Display -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg px-4 border-l-4 border-gray-400">
                        <div class="col-span-7">
                            <span class="font-bold text-lg text-gray-800">Section Total Score</span>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-xl font-bold text-gray-700">185</span>
                        </div>
                        <div class="col-span-3 text-center">
                            <span class="text-xl font-bold text-gray-800">{{ number_format($totalScore, 2) }}</span>
                        </div>
                    </div>

                    <!-- Overall Total Score Display -->
                    @if($totalScore > 0)
                    <div class="grid grid-cols-12 gap-4 items-center py-6 bg-gradient-to-r from-[#0A6025]/10 to-green-50 rounded-lg px-4 border-l-4 border-[#0A6025] mt-4">
                        <div class="col-span-7">
                            <span class="font-bold text-2xl text-[#0A6025]">Overall Total Score</span>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-2xl font-bold text-[#0A6025]">185</span>
                        </div>
                        <div class="col-span-3 text-center">
                            <span class="text-3xl font-bold text-[#0A6025]">{{ number_format($totalScore, 2) }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-center gap-4 mt-8">
                    <a href="{{ route('panel.performance', ['evaluationId' => $evaluationId, 'interviewId' => request()->interviewId ?? 1]) }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                        ← Return
                    </a>
                    <button type="submit"
                            class="bg-[#0A6025] hover:bg-[#0B712C] text-white px-8 py-3 rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                        Submit ✓
                    </button>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>