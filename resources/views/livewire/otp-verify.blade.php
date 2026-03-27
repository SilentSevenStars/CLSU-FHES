<div class="flex-1 flex flex-col justify-center items-center bg-white px-6 sm:px-8 lg:px-12 xl:px-16">
    <div class="w-full max-w-md">

        {{-- Title --}}
        <div class="mb-8">
            <h1 class="text-4xl sm:text-5xl font-bold text-[#0B712C] leading-tight">
                <span class="block">Verify</span>
                <span class="block">Your Email</span>
            </h1>
            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                We sent a 6-digit OTP to your registered email address.
                Enter it below to activate your account.
            </p>
        </div>

        {{-- Session flash (after redirect) --}}
        @if (session('status'))
            <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-sm text-green-800 leading-5">{{ session('status') }}</p>
            </div>
        @endif

        {{-- Livewire status message --}}
        @if ($statusMessage)
            <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-sm text-green-800 leading-5">{{ $statusMessage }}</p>
            </div>
        @endif

        {{-- Livewire error message --}}
        @if ($errorMessage)
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-sm text-red-700 leading-5">{{ $errorMessage }}</p>
            </div>
        @endif

        {{-- OTP digit inputs --}}
        {{--
            Each input is individually wire:model-bound (d1–d6).
            Alpine.js handles auto-advance, backspace, and paste.
            On input/backspace, it updates the Livewire property via
            $wire.set() to keep server state in sync without full round-trips.
        --}}
        <div
            x-data="otpBox()"
            class="mb-8"
        >
            <div class="flex justify-between gap-2" @paste.window.prevent="handlePaste($event)">

                @foreach(['d1','d2','d3','d4','d5','d6'] as $i => $field)
                <input
                    type="text"
                    inputmode="numeric"
                    maxlength="1"
                    id="otp_{{ $i }}"
                    wire:model.live="{{ $field }}"
                    x-ref="otp{{ $i }}"
                    @input="handleInput({{ $i }}, $event)"
                    @keydown.backspace="handleBackspace({{ $i }}, $event)"
                    @keydown.left.prevent="focus({{ $i }} - 1)"
                    @keydown.right.prevent="focus({{ $i }} + 1)"
                    autocomplete="one-time-code"
                    class="w-12 h-14 text-center text-2xl font-bold rounded-xl border-2 transition duration-150 focus:outline-none"
                    :class="$refs.otp{{ $i }} && $refs.otp{{ $i }}.value
                        ? 'border-[#0B712C] bg-white text-[#0B712C]'
                        : 'border-gray-200 bg-gray-100 text-gray-900 focus:border-[#0B712C] focus:bg-white'"
                >
                @endforeach

            </div>
        </div>

        {{-- Verify Button --}}
        <button
            wire:click="verify"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-70 cursor-not-allowed"
            class="w-full py-4 px-6 bg-[#0A6025] hover:bg-[#0B712C] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0B712C] mb-4"
        >
            <span wire:loading.remove wire:target="verify">Verify OTP</span>
            <span wire:loading wire:target="verify">Verifying...</span>
        </button>

        {{-- Resend OTP --}}
        <div
            class="text-center"
            x-data="resendTimer()"
            x-on:otp-resent.window="restart()"
        >
            <p class="text-sm text-gray-500 mb-2">Didn't receive the code?</p>

            <button
                wire:click="resend"
                wire:loading.attr="disabled"
                :disabled="!canResend"
                :class="canResend
                    ? 'text-[#0B712C] hover:text-[#0A6025] cursor-pointer font-semibold'
                    : 'text-gray-400 cursor-not-allowed'"
                class="text-sm transition duration-150 focus:outline-none"
            >
                <span wire:loading.remove wire:target="resend">
                    <span x-show="canResend">Resend OTP</span>
                    <span x-show="!canResend">
                        Resend available in <span class="font-semibold" x-text="countdown"></span>s
                    </span>
                </span>
                <span wire:loading wire:target="resend">Sending...</span>
            </button>
        </div>

        {{-- Use a different account --}}
        <div class="text-center mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="text-xs text-gray-400 hover:text-gray-600 transition duration-150"
                >
                    Use a different account
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    function otpBox() {
        return {
            handleInput(index, event) {
                const val = event.target.value.replace(/\D/g, '');
                event.target.value = val ? val[val.length - 1] : '';
                if (event.target.value && index < 5) {
                    this.focus(index + 1);
                }
            },

            handleBackspace(index, event) {
                if (!event.target.value && index > 0) {
                    this.focus(index - 1);
                    // Clear the previous box via Livewire
                    const fields = ['d1','d2','d3','d4','d5','d6'];
                    $wire.set(fields[index - 1], '');
                }
            },

            handlePaste(event) {
                const text = (event.clipboardData || window.clipboardData)
                    .getData('text')
                    .replace(/\D/g, '')
                    .slice(0, 6);

                const fields = ['d1','d2','d3','d4','d5','d6'];
                text.split('').forEach((char, i) => {
                    $wire.set(fields[i], char);
                    const el = this.$refs['otp' + i];
                    if (el) el.value = char;
                });

                this.$nextTick(() => this.focus(Math.min(text.length, 5)));
            },

            focus(index) {
                if (index < 0 || index > 5) return;
                this.$nextTick(() => {
                    const el = this.$refs['otp' + index];
                    if (el) el.focus();
                });
            },
        }
    }

    function resendTimer() {
        return {
            canResend: false,
            countdown: 60,
            timer: null,

            init() {
                this.start();
            },

            start() {
                this.canResend = false;
                this.countdown = 60;
                clearInterval(this.timer);
                this.timer = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) {
                        clearInterval(this.timer);
                        this.canResend = true;
                    }
                }, 1000);
            },

            restart() {
                this.start();
            },
        }
    }
</script>