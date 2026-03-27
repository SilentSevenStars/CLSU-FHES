<?php

namespace App\Livewire;

use App\Mail\OtpMail;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Rule;
use Livewire\Component;

class OtpVerify extends Component
{
    // Individual digit fields bound to the 6 input boxes
    #[Rule('required|digits:1')]
    public string $d1 = '';
    public string $d2 = '';
    public string $d3 = '';
    public string $d4 = '';
    public string $d5 = '';
    public string $d6 = '';

    public string $statusMessage = '';
    public string $errorMessage  = '';

    // Countdown seconds remaining before resend is allowed
    public int $resendCooldown = 60;
    public bool $canResend = false;

    // -------------------------------------------------------------------------
    // Lifecycle
    // -------------------------------------------------------------------------

    public function mount(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectByRole($user);
            return;
        }

        // Auto-send OTP on first load if none exists or existing is expired
        $existing = OtpVerification::where('user_id', $user->id)->latest()->first();

        if (! $existing || $existing->isExpired()) {
            $this->generateAndSendOtp($user);
            $this->statusMessage = 'An OTP has been sent to your email address.';
        }
    }

    // -------------------------------------------------------------------------
    // Actions
    // -------------------------------------------------------------------------

    /**
     * Called when user clicks "Verify OTP".
     */
    public function verify(): void
    {
        $this->errorMessage  = '';
        $this->statusMessage = '';

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user || $user->hasVerifiedEmail()) {
            $this->redirectByRole($user);
            return;
        }

        // Combine the 6 digit fields
        $otp = $this->d1 . $this->d2 . $this->d3 . $this->d4 . $this->d5 . $this->d6;

        if (strlen($otp) !== 6 || ! ctype_digit($otp)) {
            $this->errorMessage = 'Please enter all 6 digits of your OTP.';
            return;
        }

        $otpRecord = OtpVerification::where('user_id', $user->id)->latest()->first();

        if (! $otpRecord) {
            $this->errorMessage = 'No OTP found. Please request a new one.';
            return;
        }

        if ($otpRecord->isExpired()) {
            $otpRecord->delete();
            $this->errorMessage = 'Your OTP has expired. Please request a new one.';
            $this->clearDigits();
            return;
        }

        if (! Hash::check($otp, $otpRecord->otp)) {
            $this->errorMessage = 'Invalid OTP. Please check your email and try again.';
            $this->clearDigits();
            return;
        }

        // ✅ Valid — mark verified and clean up
        $user->markEmailAsVerified();
        $otpRecord->delete();

        session()->flash('status', 'Email verified successfully! Welcome to CLSU FHES.');
        $this->redirectByRole($user);
    }

    /**
     * Called when user clicks "Resend OTP".
     */
    public function resend(): void
    {
        $this->errorMessage  = '';
        $this->statusMessage = '';

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user || $user->hasVerifiedEmail()) {
            return;
        }

        $this->generateAndSendOtp($user);
        $this->clearDigits();

        $this->canResend      = false;
        $this->resendCooldown = 60;
        $this->statusMessage  = 'A new OTP has been sent to your email address.';

        // Restart the cooldown timer on the frontend
        $this->dispatch('otp-resent');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function generateAndSendOtp(\App\Models\User $user): void
    {
        OtpVerification::where('user_id', $user->id)->delete();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpVerification::create([
            'user_id'    => $user->id,
            'otp'        => Hash::make($otp),
            'expires_at' => now()->addMinutes(10),
        ]);

        // $user->email and $user->name are auto-decrypted by the Encrypted cast
        Mail::to($user->email)->send(new OtpMail($otp, $user->name ?? 'User'));
    }

    private function clearDigits(): void
    {
        $this->d1 = $this->d2 = $this->d3 = $this->d4 = $this->d5 = $this->d6 = '';
    }

    private function redirectByRole(\App\Models\User $user): void
    {
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            $this->redirect(route('admin.dashboard'), navigate: true);
            return;
        }

        if ($user->hasRole('panel')) {
            $this->redirect(route('panel.dashboard'), navigate: true);
            return;
        }

        if ($user->hasRole('nbc')) {
            $this->redirect(route('nbc.dashboard'), navigate: true);
            return;
        }

        $this->redirect(route('dashboard'), navigate: true);
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render()
    {
        return view('livewire.otp-verify')
            ->layout('layouts.guest');
    }
}