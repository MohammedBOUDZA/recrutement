<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'emploi_id', 'status', // Status can be 'pending', 'accepted', 'rejected'
    ];

    // Relationship with Chercheur
    public function chercheur()
    {
        return $this->belongsTo(Chercheur::class);
    }

    // Relationship with Emploi
    public function emploi()
    {
        return $this->belongsTo(Emploi::class);
    }
}