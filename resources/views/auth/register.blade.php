<x-guest-layout>
    <div class="flex-1 flex flex-col justify-center items-center bg-white px-6 sm:px-8 lg:px-12 xl:px-16">
        <div class="w-full max-w-md">
            <div class="relative w-full min-h-[600px]" id="authContainer">
                <div class="absolute top-0 left-0 w-full transition-opacity duration-300 ease-in-out sign-up-form">
                    <form method="POST" action="{{ route('register') }}" class="w-full" x-data="registerForm()">
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

                        <!-- Progress Indicator -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium" :class="currentStep === 1 ? 'text-[#0B712C]' : 'text-gray-500'">Personal Info</span>
                                <span class="text-sm font-medium" :class="currentStep === 2 ? 'text-[#0B712C]' : 'text-gray-500'">Account Details</span>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex-1 h-2 rounded-full" :class="currentStep >= 1 ? 'bg-[#0B712C]' : 'bg-gray-200'"></div>
                                <div class="flex-1 h-2 rounded-full" :class="currentStep >= 2 ? 'bg-[#0B712C]' : 'bg-gray-200'"></div>
                            </div>
                        </div>

                        <!-- Step 1: Personal Information -->
                        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                            <!-- First Name Field -->
                            <div class="mb-5">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="first_name" name="first_name" x-model="firstName" required
                                        placeholder="First Name"
                                        class="block w-full pl-12 pr-4 py-4 bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-[#0B712C] focus:bg-white transition duration-150 ease-in-out text-gray-900 placeholder-gray-500">
                                </div>
                            </div>

                            <!-- Middle Name Field -->
                            <div class="mb-5">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="middle_name" name="middle_name" x-model="middleName"
                                        placeholder="Middle Name (Optional)"
                                        class="block w-full pl-12 pr-4 py-4 bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-[#0B712C] focus:bg-white transition duration-150 ease-in-out text-gray-900 placeholder-gray-500">
                                </div>
                            </div>

                            <!-- Last Name Field -->
                            <div class="mb-5">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="last_name" name="last_name" x-model="lastName" required
                                        placeholder="Last Name"
                                        class="block w-full pl-12 pr-4 py-4 bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-[#0B712C] focus:bg-white transition duration-150 ease-in-out text-gray-900 placeholder-gray-500">
                                </div>
                            </div>

                            <!-- Suffix Field -->
                            <div class="mb-5">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                    </div>
                                    <select id="suffix" name="suffix" x-model="suffix"
                                        class="block w-full pl-12 pr-10 py-4 bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-[#0B712C] focus:bg-white transition duration-150 ease-in-out text-gray-900 cursor-pointer appearance-none">
                                        <option value="">Suffix (Optional)</option>
                                        <option value="Jr.">Jr.</option>
                                        <option value="Sr.">Sr.</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                        <option value="IV">IV</option>
                                        <option value="V">V</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Next Button -->
                            <div class="mt-8">
                                <button type="button" @click="goToStep(2)"
                                    :disabled="!firstName || !lastName"
                                    :class="firstName && lastName ? 'bg-[#0A6025] hover:bg-[#0B712C]' : 'bg-gray-400 cursor-not-allowed'"
                                    class="w-full py-4 px-6 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0B712C]">
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Account Details -->
                        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                            <!-- Email Field -->
                            <div class="mb-5">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                    <input type="email" name="email" x-model="email" required
                                        autocomplete="username" placeholder="Email"
                                        class="block w-full pl-12 pr-4 py-4 bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-[#0B712C] focus:bg-white transition duration-150 ease-in-out text-gray-900 placeholder-gray-500">
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input 
                                        :type="showPassword ? 'text' : 'password'" 
                                        name="password" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Password"
                                        x-model="password"
                                        @input="checkPasswordStrength"
                                        minlength="8"
                                        maxlength="32"
                                        class="block w-full pl-12 pr-12 py-4 bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-[#0B712C] focus:bg-white transition duration-150 ease-in-out text-gray-900 placeholder-gray-500">
                                    <button 
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Password Strength Indicator -->
                            <div class="mb-5">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div 
                                            class="h-full transition-all duration-300"
                                            :class="{
                                                'w-0 bg-gray-300': strength === 0,
                                                'w-1/4 bg-red-500': strength === 1,
                                                'w-2/4 bg-orange-500': strength === 2,
                                                'w-3/4 bg-yellow-500': strength === 3,
                                                'w-full bg-green-500': strength === 4
                                            }">
                                        </div>
                                    </div>
                                    <span 
                                        class="text-xs font-medium min-w-[60px]"
                                        :class="{
                                            'text-gray-500': strength === 0,
                                            'text-red-500': strength === 1,
                                            'text-orange-500': strength === 2,
                                            'text-yellow-600': strength === 3,
                                            'text-green-600': strength === 4
                                        }"
                                        x-text="strengthText">
                                    </span>
                                </div>
                                
                                <!-- Password Requirements -->
                                <div class="space-y-1 text-xs">
                                    <div class="flex items-center gap-2" :class="hasMinLength ? 'text-green-600' : 'text-gray-500'">
                                        <svg class="w-4 h-4" :class="hasMinLength ? 'text-green-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>8-32 characters</span>
                                    </div>
                                    <div class="flex items-center gap-2" :class="hasUppercase ? 'text-green-600' : 'text-gray-500'">
                                        <svg class="w-4 h-4" :class="hasUppercase ? 'text-green-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>One uppercase letter</span>
                                    </div>
                                    <div class="flex items-center gap-2" :class="hasLowercase ? 'text-green-600' : 'text-gray-500'">
                                        <svg class="w-4 h-4" :class="hasLowercase ? 'text-green-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>One lowercase letter</span>
                                    </div>
                                    <div class="flex items-center gap-2" :class="hasNumber ? 'text-green-600' : 'text-gray-500'">
                                        <svg class="w-4 h-4" :class="hasNumber ? 'text-green-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>One number</span>
                                    </div>
                                    <div class="flex items-center gap-2" :class="hasSpecialChar ? 'text-green-600' : 'text-gray-500'">
                                        <svg class="w-4 h-4" :class="hasSpecialChar ? 'text-green-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>One special character (!@#$%^&*)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-5">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input 
                                        :type="showConfirmPassword ? 'text' : 'password'" 
                                        name="password_confirmation" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="Confirm Password"
                                        x-model="confirmPassword"
                                        class="block w-full pl-12 pr-12 py-4 bg-gray-100 border-0 rounded-xl focus:ring-2 focus:ring-[#0B712C] focus:bg-white transition duration-150 ease-in-out text-gray-900 placeholder-gray-500">
                                    <button 
                                        type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <p class="text-sm text-gray-600 mb-5 text-center">
                                Already registered?
                                <a href="{{ route('login') }}"
                                    class="text-sm font-semibold text-[#0B712C] hover:text-[#0A6025] transition duration-150 no-underline">Sign in</a>
                            </p>

                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <button type="button" @click="goToStep(1)"
                                    class="flex-1 py-4 px-6 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Back
                                </button>
                                <button type="submit"
                                    class="flex-1 py-4 px-6 bg-[#0A6025] hover:bg-[#0B712C] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0B712C]">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function registerForm() {
            return {
                currentStep: 1,
                firstName: '{{ old("first_name") }}',
                middleName: '{{ old("middle_name") }}',
                lastName: '{{ old("last_name") }}',
                suffix: '{{ old("suffix") }}',
                email: '{{ old("email") }}',
                password: '',
                confirmPassword: '',
                showPassword: false,
                showConfirmPassword: false,
                strength: 0,
                strengthText: '',
                hasMinLength: false,
                hasUppercase: false,
                hasLowercase: false,
                hasNumber: false,
                hasSpecialChar: false,

                goToStep(step) {
                    this.currentStep = step;
                },

                checkPasswordStrength() {
                    const pwd = this.password;
                    
                    // Check requirements
                    this.hasMinLength = pwd.length >= 8 && pwd.length <= 32;
                    this.hasUppercase = /[A-Z]/.test(pwd);
                    this.hasLowercase = /[a-z]/.test(pwd);
                    this.hasNumber = /[0-9]/.test(pwd);
                    this.hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(pwd);

                    // Calculate strength
                    let score = 0;
                    if (this.hasMinLength) score++;
                    if (this.hasUppercase) score++;
                    if (this.hasLowercase) score++;
                    if (this.hasNumber) score++;
                    if (this.hasSpecialChar) score++;

                    this.strength = score;

                    // Set strength text
                    if (score === 0) {
                        this.strengthText = '';
                    } else if (score <= 2) {
                        this.strengthText = 'Weak';
                    } else if (score === 3) {
                        this.strengthText = 'Fair';
                    } else if (score === 4) {
                        this.strengthText = 'Good';
                    } else {
                        this.strengthText = 'Strong';
                    }
                }
            }
        }
    </script>

    <style>
        /* Form toggle animations using Tailwind classes */
        /* Make sign-up visible by default on the register page */
        .sign-up-form {
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        /* When the container is 'active' (toggle to sign in), hide sign-up and show sign-in */
        #authContainer.active .sign-up-form {
            opacity: 0 !important;
            pointer-events: none !important;
        }

        #authContainer.active .sign-in-form {
            opacity: 1 !important;
            pointer-events: auto !important;
        }
    </style>
</x-guest-layout>