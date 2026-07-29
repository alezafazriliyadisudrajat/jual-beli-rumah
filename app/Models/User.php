<?php

namespace App\Models;

# use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role_id', 'phone', 'is_verified', 'avatar', 'last_seen_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    # Menggunakan trait HasApiTokens, HasFactory, dan Notifiable
    use HasApiTokens, HasFactory, Notifiable;

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tenant_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Dapatkan atribut yang harus dikonversi tipe datanya.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }
}
