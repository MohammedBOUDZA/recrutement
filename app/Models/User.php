<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function chercheur()
    {
        return $this->hasOne(Chercheur::class);
    }

    public function savedJobs()
    {
        return $this->belongsToMany(Emploi::class, 'saved_jobs')
                    ->withTimestamps();
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function isJobSeeker()
    {
        return $this->role === 'chercheur';
    }

    public function isRecruiter()
    {
        return $this->role === 'recruteur';
    }

    public function hasSavedJob(Emploi $emploi)
    {
        return $this->savedJobs()->where('emploi_id', $emploi->id)->exists();
    }

    public function hasAppliedToJob(Emploi $emploi)
    {
        return $this->applications()->where('emploi_id', $emploi->id)->exists();
    }
}