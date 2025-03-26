<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emploi extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise_id',
        'title',
        'description',
        'location',
        'salary',
        'emploi_type',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'emplois_id');
    }

    public function hasApplied($user)
    {
        if (!$user || $user->role !== 'chercheur') {
            return false;
        }

        $chercheur = Chercheur::where('user_id', $user->id)->first();
        
        if (!$chercheur) {
            return false;
        }

        return $this->applications()
            ->where('chercheurs_id', $chercheur->id)
            ->exists();
    }
}