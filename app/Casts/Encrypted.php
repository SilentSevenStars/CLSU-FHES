<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

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
            return $value;
        }
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        return Crypt::encrypt($value);
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
