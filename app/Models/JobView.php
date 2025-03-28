<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobView extends Model
{
    use HasFactory;

    protected $fillable = [
        'emploi_id',
        'user_id',
        'ip_address',
        'user_agent'
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Emploi::class, 'emploi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 