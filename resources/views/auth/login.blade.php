<x-guest-layout>
    <div class="flex-1 flex flex-col justify-center items-center bg-white px-6 sm:px-8 lg:px-12 xl:px-16">
        <div class="w-full max-w-md">
            <div class="relative w-full min-h-[500px]" id="authContainer">
                <div class="absolute top-0 left-0 w-full transition-opacity duration-300 ease-in-out sign-in-form">
                    <form method="POST" action="{{ route('login') }}" class="w-full">
                        @csrf
                        <!-- Title -->
                        <div class="mb-10">
                            <h1 class="text-4xl sm:text-5xl font-bold text-[#0B712C] leading-tight">
                                <span class="block">Faculty Hiring</span>
                                <span class="block">Evaluation</span>
                                <span class="block">System</span>
                            </h1>
                        </div>

                        <x-validation-errors class="mb-4" />

                        @session('status')
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800 leading-5 tracking-wide">{{ $value }}</p>
                        </div>
                        @endsession

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

                        <!-- Password Field -->
                        <div class="mb-5">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input type="password" name="password" required autocomplete="current-password"
                                    placeholder="Password"
                                    class="block w-full pl-12 pr-4 py-4 bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-[#0B712C] focus:bg-white transition duration-150 ease-in-out text-gray-900 placeholder-gray-500">
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 mb-5 text-center">
                            Do you have an account?
                            <a href="{{ route('register') }}"
                                class="text-sm font-semibold text-[#0B712C] hover:text-[#0A6025] transition duration-150 no-underline">Sign
                                up</a>
                        </p>

                        <!-- Submit Button -->
                        <div class="mb-5">
                            <button type="submit"
                                class="w-full py-4 px-6 bg-[#0A6025] hover:bg-[#0B712C] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0B712C]">
                                LOG IN
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                        <div class="text-center">
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-semibold text-[#0B712C] hover:text-[#0A6025] transition duration-150">
                                Forgot Password?
                            </a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Form toggle animations using Tailwind classes */
        .sign-in-form {
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        #authContainer.active .sign-in-form {
            opacity: 0 !important;
            pointer-events: none !important;
        }

        #authContainer.active .sign-up-form {
            opacity: 1 !important;
            pointer-events: auto !important;
        }
    </style>
</x-guest-layout>