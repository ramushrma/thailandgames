<?php

namespace App\Models;

<<<<<<< HEAD
// use Illuminate\Contracts\Auth\MustVerifyEmail;
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'u_id',
        'image',
        'status',
        'referral_code',
<<<<<<< HEAD
        'referrer_id',
        'bonus',
        'wallet',
        'accountNo',
        'role_id',
        'admin_id',
        'vendor_id',
        'created_by',
        'permissions',
=======
        'wallet',
        'accountNo'
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
<<<<<<< HEAD
        'password',
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
