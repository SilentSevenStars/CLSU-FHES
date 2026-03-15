<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Casts\Encrypted;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'name' => Encrypted::class,
            'email' => Encrypted::class,
        ];
    }

    /**
     * Get the panel associated with this user.
     */
    public function panel()
    {
        return $this->hasOne(Panel::class);
    }

    /**
     * Get the applicant associated with this user.
     */
    public function applicant()
    {
        return $this->hasOne(Applicant::class);
    }

    /**
     * Get the NBC committee associated with this user.
     */
    public function nbcCommittee()
    {
        return $this->hasOne(NbcCommittee::class);
    }
}

