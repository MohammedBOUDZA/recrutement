<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'description', 'website', 'location', 'industry'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emplois()
    {
        return $this->hasMany(Emploi::class, 'entreprise_id');
    }
    
}