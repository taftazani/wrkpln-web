<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'profile_image',
        'tgl_lahir',
        'tgl_masuk',
        'phone',
        'role',
        'salary',
        'norek',
        'bank_name',
        'tipe_gajian',
        'password',
        'otp',
        'otp_expiry',
        'otp_last_sent_at',
        'company_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expiry' => 'datetime',
        'password' => 'hashed',
        'otp_last_sent_at',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function area()
    {
        return $this->belongsToMany(Place::class, 'pivot_user_areas');
    }

    public function hasPermission($permission)
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->contains($permission);
    }

    public function salaries()
    {
        return $this->hasMany(Payment::class, 'id_user', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}