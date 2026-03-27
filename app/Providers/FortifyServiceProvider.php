<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Mail\OtpMail;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // -------------------------------------------------------------------------
        // Custom login: handles encrypted email + role/archive checks
        // -------------------------------------------------------------------------
        Fortify::authenticateUsing(function (Request $request) {
            $users = User::all();
            $user  = null;

            foreach ($users as $u) {
                $storedEmail = $u->getAttributes()['email'];
                try {
                    $decrypted = decrypt($storedEmail);
                } catch (\Throwable $e) {
                    $decrypted = $storedEmail;
                }
                if ($decrypted === $request->email) {
                    $user = $u;
                    break;
                }
            }

            if ($user && Hash::check($request->password, $user->password)) {

                if ($user->archive) {
                    session()->flash('error', 'Your account has been archived. Please contact the administrator.');
                    return null;
                }

                if ($user->hasRole('applicant') && ! $user->hasVerifiedEmail()) {
                    session()->flash('error', 'Applicants must verify their email before logging in.');
                    return null;
                }

                return $user;
            }

            return null;
        });

        // -------------------------------------------------------------------------
        // Rate limiting
        // -------------------------------------------------------------------------
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // -------------------------------------------------------------------------
        // Login Response: redirect by role
        // -------------------------------------------------------------------------
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    $user = $request->user();

                    if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
                        return redirect(route('admin.dashboard'));
                    } elseif ($user->hasRole('panel')) {
                        return redirect(route('panel.dashboard'));
                    } elseif ($user->hasRole('nbc')) {
                        return redirect(route('nbc.dashboard'));
                    }

                    return redirect(route('dashboard'));
                }
            };
        });

        // -------------------------------------------------------------------------
        // Register Response: send OTP right after account creation,
        // then redirect to the Livewire OTP verification page.
        // -------------------------------------------------------------------------
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    /** @var \App\Models\User $user */
                    $user = $request->user();

                    // Clear stale OTPs and issue a fresh one
                    OtpVerification::where('user_id', $user->id)->delete();

                    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                    OtpVerification::create([
                        'user_id'    => $user->id,
                        'otp'        => Hash::make($otp),
                        'expires_at' => now()->addMinutes(10),
                    ]);

                    // Both $user->email and $user->name are auto-decrypted by the Encrypted cast
                    Mail::to($user->email)->send(
                        new OtpMail($otp, $user->name ?? 'User')
                    );

                    return redirect()->route('otp.verify')
                        ->with('status', 'Account created! Please check your email for your OTP.');
                }
            };
        });
    }
}