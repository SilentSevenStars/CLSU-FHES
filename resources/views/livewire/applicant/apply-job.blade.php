<div>
    <div class="flex-1 bg-gradient-to-br from-slate-50 to-green-50 p-6 overflow-auto min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg shadow animate-fadeIn">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header Section -->
            <div class="mb-8 animate-fadeIn">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-extrabold bg-[#0A6025] bg-clip-text text-transparent mb-2">
                            Available Positions
                        </h1>
                        <p class="text-gray-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0A6025]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Browse and apply for available job positions
                        </p>
                    </div>
                </div>
            </div>

            @if($positions->isEmpty())

                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-xl p-12 text-center animate-fadeIn">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full p-6 w-24 h-24 mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No Available Positions</h3>
                        <p class="text-gray-500">There are no job positions available at the moment. Please check back later.</p>
                    </div>
                </div>

            @else

                <!-- Positions Grid -->
                <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-8">
                    @foreach($positions as $index => $position)
                        <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 animate-slideInLeft border-l-4 border-[#0A6025]"
                            style="animation-delay: {{ $index * 0.1 }}s;">
                            <div class="p-6">

                                <!-- Icon -->
                                <div class="bg-gradient-to-br from-yellow-500 to-[#0A6025] rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300 w-16 h-16 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>

                                <!-- Job Title -->
                                <h5 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-[#0A6025] transition-colors duration-300">
                                    {{ $position->name }}
                                </h5>

                                <!-- Department -->
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-600">{{ $position->department }}</p>
                                </div>

                                <!-- Date Range -->
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($position->start_date)->format('M d, Y') }} -
                                        {{ \Carbon\Carbon::parse($position->end_date)->format('M d, Y') }}
                                    </p>
                                </div>

                                <!-- Apply Button or Disabled Button -->
                                @if(in_array($position->id, $applied))
                                    <button class="block w-full text-center bg-gray-300 text-gray-500 font-semibold rounded-lg text-sm px-4 py-3 cursor-not-allowed">
                                        Already Applied
                                    </button>
                                @else
                                    <a href="{{ route('job-application', ['position_id' => $position->id]) }}"
                                        class="block w-full text-center text-white bg-[#0A6025] hover:bg-[#0B712C] focus:ring-4 focus:ring-[#0A6025] 
                                                font-semibold rounded-lg text-sm px-4 py-3 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                        Apply Now
                                    </a>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>
