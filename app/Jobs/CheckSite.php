<?php

namespace App\Jobs;

use App\Check;
use App\Monitor;
use App\Notifications\CertificateExpiresSoon;
use App\Notifications\CertificateExpiresToday;
use App\Notifications\CertificateIsInvalid;
use App\Notifications\DomainReadyForRenewal;
use App\Notifications\DomainStatusChanged;
use App\SiteHealth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Notification;

class CheckSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Check $check;

    public Monitor $monitor;

    public SiteHealth $health;

    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

    public function handle()
    {
        $this->health = SiteHealth::make($this->monitor->site);
        $this->check = $this->monitor->createCheckFromLookup($this->health);

        $this->handleCertificateValidity();
        $this->handleCertificateExpiry();
        $this->handleDomainExpiry();

        $this->monitor->fill([
            'certificate_expires_at' => $this->check->certificate_expires_at,
            'is_valid' => $this->check->is_valid,
            'is_domain_valid' => $this->health->isDomainValid(),
            'domain_status' => $this->health->domainStatus(),
            'last_checked_at' => Date::now(),
        ])->save();
    }

    protected function handleCertificateValidity(): void
    {
        if (! $this->check->is_valid && $this->health->monitor->is_valid) {
            Notification::route('mail', 'michael.dyrynda@maxo.com.au')
                ->notify(new CertificateIsInvalid($this->monitor));
        }
    }

    protected function handleCertificateExpiry(): void
    {
        if (optional($this->check->certificate_expires_at)->diffInDays() == 14) {
            Notification::route('mail', 'michael.dyrynda@maxo.com.au')
                ->notify(new CertificateExpiresSoon($this->monitor));
        }

        if ($this->check->is_expired && optional($this->check->certificate_expires_at)->isDay(Date::today())) {
            Notification::route('mail', 'michael.dyrynda@maxo.com.au')
                ->notify(new CertificateExpiresToday($this->monitor));
        }
    }

    protected function handleDomainExpiry(): void
    {
        if ($this->check->domain_status == 'ok' && $this->monitor->domain_status !== 'ok') {
            // domain has moved into renewal period
            Notification::route('mail', 'michael.dyrynda@maxo.com.au')
                ->notify(new DomainReadyForRenewal($this->monitor));

            $this->monitor->fill(['domain_expires_at' => Date::now()->addDays(90)]);

            return;
        }

        if ($this->monitor->domain_status && ($this->monitor->domain_status !== $this->check->domain_status)) {
            // Domain has changed registration status
            Notification::route('mail', 'michael.dyrynda@maxo.com.au')
                ->notify(new DomainStatusChanged(
                    $this->check, 
                    $this->monitor->domain_status,
                    $this->check->domain_status
                ));

            $this->monitor->fill(['domain_expires_at' => null]);
        }
    }
}
