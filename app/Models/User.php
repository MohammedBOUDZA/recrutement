<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

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
        'google_id',
        'linkedin_id',
        'is_active',
        'company_name',
        'company_description',
        'company_website',
        'company_size',
        'industry',
        'company_logo',
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
        return $this->belongsToMany(Emploi::class, 'saved_jobs', 'user_id', 'emploi_id')
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
        return $this->savedJobs()->where('emplois.id', $emploi->id)->exists();
    }

    public function hasAppliedToJob(Emploi $emploi)
    {
        return $this->applications()->where('emplois_id', $emploi->id)->exists();
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['name', 'email', 'role'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function entreprise()
    {
        return $this->hasOne(Entreprise::class);
    }
}