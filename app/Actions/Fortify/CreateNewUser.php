<?php

namespace App\Actions\Fortify;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:5'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->max(32)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 32 characters.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
            'password.uncompromised' => 'This password has appeared in a data breach and should not be used.',
        ])->validate();

        $middleInitial = '';
        if (!empty($input['middle_name'])) {
            $middleInitial = strtoupper(substr($input['middle_name'], 0, 1)) . '.';
        }
        
        $nameParts = [$input['first_name'], $middleInitial, $input['last_name']];
        
        if (!empty($input['suffix'])) {
            $nameParts[] = $input['suffix'];
        }

        $name = trim(implode(' ', array_filter($nameParts)));

        $user = User::create([
            'name' => $name,
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        Applicant::create([
            'first_name' => $input['first_name'],
            'middle_name' => $input['middle_name'] ?? null,
            'last_name' => $input['last_name'],
            'suffix' => $input['suffix'] ?? null,
            'user_id' => $user->id,
        ]);

        return $user;
    }
}