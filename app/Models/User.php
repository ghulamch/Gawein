<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    




    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'ktp_path',
        'verification_status',
    ];

    




    protected $hidden = [
        'password',
        'remember_token',
    ];

    




    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function companyProfile()
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function seekerProfile()
    {
        return $this->hasOne(SeekerProfile::class);
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }
}
