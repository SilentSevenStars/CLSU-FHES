<?php

namespace App\Actions\Fortify;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $middleInitial = '';
        if (!empty($input['middle_name'])) {
            $middleInitial = strtoupper(substr($input['middle_name'], 0, 1)) . '.';
        }

        $name = trim($input['first_name'] . ' ' . $middleInitial . ' ' . $input['last_name']);

        $user = User::create([
            'name' => $name,
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        Applicant::create([
            'first_name' => $input['first_name'],
            'middle_name' => $input['middle_name'] ?? null,
            'last_name' => $input['last_name'],
            'user_id' => $user->id,
        ]);

        return $user;
    }
}
