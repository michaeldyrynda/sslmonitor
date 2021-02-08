<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Date;

class Check extends Model
{
    use HasFactory;

    protected $casts = [
        'additional_domains' => 'array',
        'is_valid' => 'boolean',
        'valid_from' => 'datetime',
        'certificate_expires_at' => 'datetime',
        'domain_expires_at' => 'datetime',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        return optional($this->certificate_expires_at)->lte(Date::now()) ?: false;
    }
}
