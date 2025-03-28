<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($skill) {
            if (empty($skill->slug)) {
                $skill->slug = Str::slug($skill->name);
            }
        });

        static::updating(function ($skill) {
            if ($skill->isDirty('name')) {
                $skill->slug = Str::slug($skill->name);
            }
        });
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Emploi::class, 'job_skill')
                    ->withPivot('level')
                    ->withTimestamps();
    }
} 