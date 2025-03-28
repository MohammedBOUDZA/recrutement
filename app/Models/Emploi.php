<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Emploi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entreprise_id',
        'title',
        'description',
        'location',
        'salary_min',
        'salary_max',
        'salary_type',
        'employment_type',
        'requirements',
        'benefits',
        'remote',
        'hybrid',
        'urgent',
        'views',
        'applications_count',
        'expires_at',
        'status'
    ];

    protected $casts = [
        'remote' => 'boolean',
        'hybrid' => 'boolean',
        'urgent' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(JobCategory::class, 'job_category_emploi')
                    ->withTimestamps();
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_skill')
                    ->withPivot('level')
                    ->withTimestamps();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'emplois_id');
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_jobs')
                    ->withPivot(['notes', 'applied', 'applied_at'])
                    ->withTimestamps();
    }

    public function views(): HasMany
    {
        return $this->hasMany(JobView::class, 'emploi_id');
    }

    public function getSalaryRangeAttribute(): string
    {
        if (!$this->salary_min && !$this->salary_max) {
            return 'Not specified';
        }

        $min = $this->salary_min ? number_format($this->salary_min) : '0';
        $max = $this->salary_max ? number_format($this->salary_max) : '∞';
        $type = $this->salary_type ? ucfirst($this->salary_type) : '';

        return "$min - $max $type";
    }

    public function getEmploymentTypeLabelAttribute(): string
    {
        return match($this->employment_type) {
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
            'contract' => 'Contract',
            'temporary' => 'Temporary',
            default => ucfirst($this->employment_type)
        };
    }

    public function getLocationTypeAttribute(): string
    {
        if ($this->remote && $this->hybrid) {
            return 'Remote & Hybrid';
        } elseif ($this->remote) {
            return 'Remote';
        } elseif ($this->hybrid) {
            return 'Hybrid';
        }
        return 'On-site';
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function incrementApplicationsCount(): void
    {
        $this->increment('applications_count');
    }

    public function decrementApplicationsCount(): void
    {
        $this->decrement('applications_count');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired() && !$this->trashed();
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
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

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('expires_at', '<=', now())
              ->orWhere('status', 'closed');
        });
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeUrgent($query)
    {
        return $query->where('urgent', true);
    }

    public function scopeRemote($query)
    {
        return $query->where('remote', true);
    }

    public function scopeHybrid($query)
    {
        return $query->where('hybrid', true);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft',
            'closed' => 'Closed',
            default => 'Unknown'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'green',
            'inactive' => 'gray',
            'draft' => 'yellow',
            'closed' => 'red',
            default => 'gray'
        };
    }
}