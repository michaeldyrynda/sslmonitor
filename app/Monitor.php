<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Date;
use Spatie\SslCertificate\SslCertificate;

class Monitor extends Model
{
    use HasFactory;

    protected $casts = [
        'certificate_expires_at' => 'datetime',
        'domain_expires_at' => 'datetime',
        'last_checked_at' => 'datetime',
    ];

    public function createCheckFromCertificate(SslCertificate $certificate): Check
    {
        return $this->checks()->create([
            'issuer' => $certificate->getIssuer(),
            'domain' => $certificate->getDomain(),
            'algorithm' => $certificate->getSignatureAlgorithm(),
            'organisation' => $certificate->getOrganization(),
            'additional_domains' => $certificate->getAdditionalDomains(),
            'sha256_fingerprint' => $certificate->getFingerprintSha256(),
            'is_valid' => $certificate->isValid(),
            'valid_from' => $certificate->validFromDate(),
            'certificate_expires_at' => $certificate->expirationDate(),
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

    public function getIsInvalidAttribute(): bool
    {
        return ! $this->is_valid;
    }
}
