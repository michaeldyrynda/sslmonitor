<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Date;

class Monitor extends Model
{
    use HasFactory;

    protected $casts = [
        'is_valid' => 'boolean',
        'is_domain_valid' => 'boolean',
        'certificate_expires_at' => 'datetime',
        'domain_expires_at' => 'datetime',
        'last_checked_at' => 'datetime',
    ];

    public function createCheckFromLookup(SiteHealth $health): Check
    {
        return $this->checks()->create([
            'issuer' => $health->certificate->getIssuer(),
            'domain' => $health->certificate->getDomain(),
            'algorithm' => $health->certificate->getSignatureAlgorithm(),
            'organisation' => $health->certificate->getOrganization(),
            'additional_domains' => $health->certificate->getAdditionalDomains(),
            'sha256_fingerprint' => $health->certificate->getFingerprintSha256(),
            'is_valid' => $health->certificate->isValid(),
            'is_domain_valid' => $health->isDomainValid(),
            'domain_status' => $health->domainStatus(),
            'valid_from' => $health->certificate->validFromDate(),
            'certificate_expires_at' => $health->certificate->expirationDate(),
        ]);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(Check::class);
    }

    public function latestCheck(): HasOne
    {
        return $this->hasOne(Check::class)->latest();
    }

    public function getExpiresInDaysAttribute(): ?int
    {
        if (($days = optional($this->certificate_expires_at)->diffInDays()) < 0) {
            return null;
        }

        return $days;
    }

    public function getIsExpiredAttribute(): bool
    {
        return optional($this->certificate_expires_at)->lte(Date::now()) ?: false;
    }

    public function getIsExpiringAttribute(): bool
    {
        return optional($this->certificate_expires_at)->diffInDays() <= 14;
    }

    public function getIsHealthyAttribute(): bool
    {
        return $this->is_valid && $this->is_domain_valid;
    }

    public function getIsInvalidAttribute(): bool
    {
        return ! $this->is_valid;
    }

    public function shouldRenewDomainAttribute(): bool
    {
        return ! is_null($this->domain_expires_at)
            && $this->domain_expires_at->diffInDays() <= 90;
    }
}
