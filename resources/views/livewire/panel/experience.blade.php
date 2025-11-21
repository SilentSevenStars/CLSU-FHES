<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="bg-white shadow-sm rounded-lg mb-6 px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Applicant Details</h1>
            <a href="{{ route('panel.applicant.view', $evaluation->job_application_id) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-150">
                View here
            </a>
        </div>

        <!-- Main Content -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-12">II. Entry Credentials and Related Experiences</h2>

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
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('education_qualification') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 2. Academic/Administrative Experience -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">2. Academic/ Administrative and Industrial/Agricultural/Teaching</span>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">25</span>
                        </div>
                        <div class="col-span-3">
                            <select wire:model.live="experience_type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option value="">Select</option>
                                @foreach($experienceTypeOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('experience_type') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 3. Passing Licensure Examination -->
                    <div class="grid grid-cols-12 gap-4 items-start py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">3. Passing appropriate Licensure Examination (max of 5)</span>
                            <div class="text-sm text-gray-600 mt-1">
                                National Certification (NC II) - 3
                            </div>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">5</span>
                        </div>
                        <div class="col-span-3">
                            <select wire:model.live="licensure_examination"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white mb-2">
                                <option value="">Select</option>
                                @foreach($licensureOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('licensure_examination') 
                                <span class="text-red-500 text-sm block mb-2">{{ $message }}</span> 
                            @enderror
                            
                            <select wire:model.live="passing_licensure_examination"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option value="">Select</option>
                                @foreach($passingLicensureOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('passing_licensure_examination') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- 4. Place in Board Examination -->
                    <div class="grid grid-cols-12 gap-4 items-center py-4 border-b">
                        <div class="col-span-7">
                            <span class="font-semibold">4. Place in Board Examination (1-10)</span>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">10</span>
                        </div>
                        <div class="col-span-3">
                            <select wire:model.live="place_board_exam"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option value="">Select</option>
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
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option value="">Select</option>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option value="">Select</option>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option value="">Select</option>
                                @foreach($schoolGraduateOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('school_graduate') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Total Score Display -->
                    @if($totalScore > 0)
                    <div class="grid grid-cols-12 gap-4 items-center py-6 bg-blue-50 rounded-lg px-4">
                        <div class="col-span-7">
                            <span class="font-bold text-xl">Total Score</span>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-lg font-semibold">185</span>
                        </div>
                        <div class="col-span-3 text-center">
                            <span class="text-2xl font-bold text-blue-600">{{ $totalScore }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-center gap-4 mt-8">
                    <a href="{{ route('panel.performance', ['evaluationId' => $evaluationId, 'interviewId' => request()->interviewId ?? 1]) }}"
                       class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-150">
                        Return
                    </a>
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-150">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>