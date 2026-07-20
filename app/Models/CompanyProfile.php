<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'industry',
        'description',
        'logo',
        'cover_photo',
        'website',
        'year_founded',
        'xp',
        'level'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
