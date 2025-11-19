<div class="flex-1 p-6 overflow-y-auto">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-xl p-6 border">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Job Application Form</h2>

            <form wire:submit.prevent="confirmSubmission" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" wire:model="first_name"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name
                                (Optional)</label>
                            <input type="text" wire:model="middle_name"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" wire:model="last_name"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" wire:model="address"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Contact
                                    Number</label>
                                <input type="text" wire:model="phone_number"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Employment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="experience" class="block text-sm font-medium text-gray-700">Experience</label>
                            <input type="number" wire:model="experience"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('experience') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="training" class="block text-sm font-medium text-gray-700">Training</label>
                            <input type="text" wire:model="training"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('training') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="eligibility" class="block text-sm font-medium text-gray-700">Eligibility</label>
                            <input type="text" wire:model="eligibility"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('eligibility') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="present_position" class="block text-sm font-medium text-gray-700">Present
                                Position</label>
                            <input type="text" wire:model="present_position"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('present_position') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="education" class="block text-sm font-medium text-gray-700">Education</label>
                            <input type="text" wire:model="education"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('education') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="other_involvement" class="block text-sm font-medium text-gray-700">Other
                                Involvement</label>
                            <input type="text" wire:model="other_involvement"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('other_involvement') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Required Documents -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Required Documents</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="requirements_file" class="block text-sm font-medium text-gray-700">Upload
                                Requirements (PDF only)</label>
                            <input type="file" wire:model="requirements_file" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100">
                            @error('requirements_file') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="history.back()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-500 transition">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div x-data x-on:show-swal-confirm.window="
        Swal.fire({
            title: 'Submit Job Application?',
            text: 'Please confirm that all details are correct before submitting.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit',
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.save();
            }
        });
    " x-on:swal\:success.window="
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: $event.detail.message,
            confirmButtonColor: '#2563eb'
        }).then(() => {
            window.location.href = '{{ route('apply-job') }}'; 
        });
    ">
    </div>
</div>