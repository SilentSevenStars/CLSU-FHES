<div class="p-6 bg-gray-50 min-h-screen">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Available Job Positions</h2>

    @if($positions->isEmpty())
        <p class="text-center text-gray-500">No available job positions at the moment.</p>
    @else
        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
            @foreach($positions as $position)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="p-5">
                        <h5 class="text-lg font-semibold text-gray-800 mb-2">{{ $position->name }}</h5>
                        <p class="text-sm text-gray-600 mb-1">
                            <i class="fas fa-building mr-1"></i> {{ $position->department }}
                        </p>
                        <p class="text-sm text-gray-600 mb-3">
                            <i class="fas fa-calendar-alt mr-1"></i> 
                            {{ \Carbon\Carbon::parse($position->start_date)->format('M d, Y') }} - 
                            {{ \Carbon\Carbon::parse($position->end_date)->format('M d, Y') }}
                        </p>
                        <a href="{{ route('job-application', ['position_id' => $position->id]) }}"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 
                                   font-medium rounded-lg text-sm px-4 py-2 text-center transition">
                            Apply Job
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
