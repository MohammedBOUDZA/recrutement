<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chercheur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'resume', 'skills', 'experience', 'education', 'phone', 'address',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Applications
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}