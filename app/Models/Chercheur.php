<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chercheur extends Model
{
    protected $fillable = [
        'user_id',
        'cv',
        'cv_path',
        'skills',
        'experience',
        'education',
        'location',
        'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}