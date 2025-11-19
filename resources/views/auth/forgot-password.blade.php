<x-guest-layout>
    <div class="flex-1 flex flex-col justify-center items-center bg-white px-6 sm:px-8 lg:px-12 xl:px-16">
        <div class="w-full max-w-md">
            <div class="relative w-full">
                <form method="POST" action="{{ route('password.email') }}" class="w-full">
                    @csrf
                    <!-- Title -->
                    <div class="mb-10">
                        <h1 class="text-4xl sm:text-5xl font-bold text-[#0B712C] leading-tight">
                            <span class="block">Forgot</span>
                            <span class="block">Password?</span>
                        </h1>
                    </div>

                    <x-validation-errors class="mb-4" />

                    @session('status')
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800 leading-5 tracking-wide">{{ $value }}</p>
                    </div>
                    @endsession

                    <!-- Description -->
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 leading-6">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </p>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-5">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="Email"
                                class="block w-full pl-12 pr-4 py-4 bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-[#0B712C] focus:bg-white transition duration-150 ease-in-out text-gray-900 placeholder-gray-500">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-5">
                        <button type="submit"
                            class="w-full py-4 px-6 bg-[#0A6025] hover:bg-[#0B712C] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0B712C]">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </div>

                    <!-- Back to Login Link -->
                    <div class="text-center">
                        <a href="{{ route('login') }}"
                            class="text-sm font-semibold text-[#0B712C] hover:text-[#0A6025] transition duration-150">
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
