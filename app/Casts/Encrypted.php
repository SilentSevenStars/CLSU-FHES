<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class Encrypted implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        try {
            return Crypt::decrypt($value);
        } catch (\Throwable $e) {
            Log::warning('Encryption decrypt failed for key {$key}: ' . $e->getMessage());
            if (app()->environment('local', 'development')) {
                throw new \RuntimeException('Encryption decrypt failed: ' . $e->getMessage(), 0, $e);
            }
            return $value;
        }
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        if (empty(config('app.key'))) {
            Log::error('APP_KEY missing - encryption disabled for key: {$key}');
            if (app()->environment('local', 'development')) {
                throw new \RuntimeException('APP_KEY not configured. Run `php artisan key:generate`');
            }
            return $value;
        }

        try {
            return Crypt::encrypt($value);
        } catch (\Throwable $e) {
            Log::error('Encryption encrypt failed for key {$key}: ' . $e->getMessage());
            if (app()->environment('local', 'development')) {
                throw new \RuntimeException('Encryption encrypt failed: ' . $e->getMessage(), 0, $e);
            }
            return $value;
        }
    }

    public function increment(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->set($model, $key, $value, $attributes);
    }

    public function decrement(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->set($model, $key, $value, $attributes);
    }
}
