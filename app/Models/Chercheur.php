<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chercheur extends Model
{
    protected $fillable = [
        'user_id',
        'cv',
        'skills',
        'experience',
        'education'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}