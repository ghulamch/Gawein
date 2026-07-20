<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeekerProfile extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'title', 'location', 'about', 'skills', 'resume', 'avatar', 'profile_completion'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
