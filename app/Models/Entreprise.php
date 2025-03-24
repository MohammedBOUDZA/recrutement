<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'company_description', 'website', 'logo', 'phone', 'address',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Jobs
    public function emplois()
    {
        return $this->hasMany(Emploi::class, 'entreprise_id');
    }
    
}