<div class="flex-1 p-6 overflow-y-auto bg-gray-50">
    <div class="max-w-6xl mx-auto">
        {{-- Flash Messages --}}
        @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800 leading-5 tracking-wide">{{ session('success') }}</p>
        </div>
        @endif

        @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-800 leading-5 tracking-wide">{{ session('error') }}</p>
        </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-[#0A6025] px-6 py-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Applicant Review</h2>
                        <p class="text-indigo-100">Application ID: #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT)
                            }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-lg text-sm font-semibold shadow-lg
                        {{ $application->status === 'approved' ? 'bg-green-500 text-white' : '' }}
                        {{ $application->status === 'declined' ? 'bg-red-500 text-white' : '' }}
                        {{ $application->status === 'pending' ? 'bg-yellow-500 text-white' : '' }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Applicant Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Applicant Information</h3>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Position: </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Full Name</span>
                                <span class="font-medium text-gray-900">
                                    {{ $application->applicant->first_name }} {{ $application->applicant->last_name }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Email</span>
                                <span class="font-medium text-gray-900">
                                    {{ $application->applicant->user->email }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Contact Number</span>
                                <span class="font-medium text-gray-900">
                                    {{ $application->applicant->phone_number }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Address</span>
                                <span class="font-medium text-gray-900">{{ $application->applicant->address }}</span>
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Present Position</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $application->present_position }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Education</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $application->education }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Experience</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $application->experience }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Training</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $application->training }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-gray-600">Other Involvement</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $application->other_involvement }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-5">Submitted Documents</h3>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Requirements File -->
                            <div class="space-y-3">
                                <h4 class="font-semibold text-gray-900">Requirement files</h4>
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    @php
                                    // store path in a variable for convenience
                                    $reqPath = $application->requirements_file ?? null;
                                    // If stored as 'requirements/xxx.pdf' the Storage::url will resolve it
                                    $reqUrl = $reqPath ? Storage::url($reqPath) : null;
                                    $reqExt = $reqPath ? strtolower(pathinfo($reqPath, PATHINFO_EXTENSION)) : null;
                                    @endphp

                                    @if (!$reqPath)
                                    <div class="p-6 text-center text-gray-500">No document submitted</div>

                                    @elseif(in_array($reqExt, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                        <img src="{{ $reqUrl }}" alt="Requirements File"
                                            class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>

                                    @else
                                    <div class="aspect-[3/2] bg-gray-100 rounded flex items-center justify-center">
                                        <a href="{{ $reqUrl }}" target="_blank"
                                            class="inline-flex items-center px-4 py-3 bg-[#0D7A2F] text-white rounded-lg hover:bg-[#0a6025] transition">
                                            View Document
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($application->status === 'pending')
                    <!-- Review Form -->
                    <div class="bg-white border-2 border-indigo-200 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-5">Review Application</h3>

                        @if($isWithinApplicationPeriod)
                        <p class="mb-4 px-4 py-3 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg">
                            The application period is still active ({{ $application->position->start_date }}
                            to {{ $application->position->end_date }}).<br>
                            You can only review applicants after the deadline.
                        </p>
                        @endif

                        <form wire:submit.prevent="submitReview" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Decision *
                                </label>

                                <select id="status" wire:model="status" wire:change="$refresh"
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-[0a6025] transition"
                                    {{ $isWithinApplicationPeriod ? 'disabled' : '' }}>

                                    <option value="">Select Decision</option>

                                    @if(!$isWithinApplicationPeriod)
                                    <option value="approve">Approve Application</option>
                                    <option value="decline">Decline Application</option>
                                    @endif
                                </select>
                            </div>

                            @if($status === 'approve' && !$isWithinApplicationPeriod)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <!-- Interview Date -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Interview Date
                                        *</label>
                                    <input type="date" wire:model="interview_date"
                                        class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm">
                                    @error('interview_date') <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Interview Room -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Interview Room
                                        *</label>
                                    <input type="text" wire:model="interview_room"
                                        class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm"
                                        placeholder="e.g. CLSU Building Room 304">
                                    @error('interview_room') <span class="text-red-600 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            @endif

                            <div class="flex justify-end space-x-3 pt-4 border-t">
                                <a href="{{ route('admin.applicant') }}"
                                    class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                                    Back to List
                                </a>

                                <button type="submit"
                                    class="inline-flex items-center px-4 py-3 bg-[#0D7A2F] text-white rounded-lg hover:bg-[#0a6025] transition"
                                    {{ $isWithinApplicationPeriod ? 'disabled' : '' }}>
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                    @else
                    <!-- Review Details -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 border-2 border-gray-200">
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Review Details</h4>
                        @if($application->admin_notes)
                        <div class="bg-white rounded-lg p-4 mb-3 border border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-1">Admin Notes:</p>
                            <p class="text-gray-900">{{ $application->admin_notes }}</p>
                        </div>
                        @endif
                        <div class="flex items-center text-sm text-gray-600">
                            <span>Reviewed on {{ $application->reviewed_at->format('F j, Y') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-start">
                        <a href="{{ route('loan-applications.index') }}"
                            class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                            Back to List
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        </main>