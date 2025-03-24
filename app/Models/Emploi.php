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

    // Relationship with Employer
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    // Relationship with Applications
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    public function entreprise()
{
    return $this->belongsTo(Entreprise::class, 'entreprise_id');
}
}