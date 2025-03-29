<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'emplois_id',
        'chercheurs_id',
        'resume',
        'resume_path',
        'cover_letter',
        'answers',
        'notes',
        'current_company',
        'current_position',
        'current_salary',
        'salary_expectation',
        'available_start_date',
        'relocation_willingness',
        'relocation_location',
        'portfolio_url',
        'linkedin_url',
        'github_url',
        'status',
        'submitted_at',
        'reviewed_at',
        'rejection_reason'
    ];

    protected $casts = [
        'answers' => 'array',
        'relocation_willingness' => 'boolean',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'current_salary' => 'decimal:2'
    ];

    // Relationship with Chercheur
    public function chercheur()
    {
        return $this->belongsTo(Chercheur::class, 'chercheurs_id');
    }

    // Relationship with Emploi
    public function emploi()
    {
        return $this->belongsTo(Emploi::class, 'emplois_id');
    }
}