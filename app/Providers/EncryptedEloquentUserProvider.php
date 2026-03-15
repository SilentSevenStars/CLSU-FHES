<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class EncryptedEloquentUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
           (count($credentials) === 1 &&
            array_key_exists('password', $credentials))) {
            return null;
        }

        // First, get all users (since we can't query encrypted email)
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (str_contains($key, 'password')) {
                continue;
            }

            if ($key === 'email') {
                // For email, we need to find by decrypting or plain
                $users = $query->get();
                foreach ($users as $user) {
                    $storedEmail = $user->getAttributes()['email'];
                    try {
                        $decrypted = decrypt($storedEmail);
                    } catch (\Throwable $e) {
                        $decrypted = $storedEmail; // assume plain
                    }
                    if ($decrypted === $value) {
                        return $user;
                    }
                }
                return null;
            }

            $query->where($key, $value);
        }

        return $query->first();
    }
}