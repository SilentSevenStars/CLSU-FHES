<x-guest-layout>
    <div class="flex-1 flex flex-col justify-center items-center bg-white px-6 sm:px-8 lg:px-12 xl:px-16">
        <div class="w-full max-w-md">
            <div class="w-full">
                <!-- Title -->
                <div class="mb-10">
                    <h1 class="text-4xl sm:text-5xl font-bold text-[#0B712C] leading-tight">
                        <span class="block">Faculty Hiring</span>
                        <span class="block">Evaluation</span>
                        <span class="block">System</span>
                    </h1>
                </div>

                @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-800 leading-5 tracking-wide">
                        {{ __('A new verification link has been sent to the email address you provided in your
                        profile settings.') }}
                    </p>
                </div>
                @endif

                <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-sm text-gray-700 leading-6">
                        {{ __('Before continuing, please verify your email address by clicking the link we just
                        emailed to you. If you didn\'t receive the email, you can request another below.') }}
                    </p>
                </div>

                <div class="flex items-center justify-between">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="py-3 px-6 bg-[#0A6025] hover:bg-[#0B712C] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0B712C]">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('profile.show') }}"
                            class="text-sm font-semibold text-[#0B712C] hover:text-[#0A6025] transition duration-150 no-underline">
                            {{ __('Edit Profile') }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-sm font-semibold text-[#0B712C] hover:text-[#0A6025] transition duration-150">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>