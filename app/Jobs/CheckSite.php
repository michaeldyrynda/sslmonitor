<?php

namespace App\Jobs;

use App\Check;
use App\Monitor;
use App\SiteHealth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class CheckSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Monitor $monitor;

    public SiteHealth $health;

    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

    public function handle()
    {
        $this->health = SiteHealth::make($this->monitor->site);

        tap($this->monitor->createCheckFromLookup($this->health), function (Check $check) {
            if (! $check->is_valid && $this->health->monitor->is_valid) {
                // dispatch notification
            }

            if (optional($check->certificate_expires_at)->diffInDays() == 14) {
                // dispatch expiration warning
            }

            if ($check->is_expired && optional($check->certificate_expires_at)->isDay(Date::today())) {
                // dispatch expired warning
            }

            $this->monitor->update([
                'certificate_expires_at' => $check->certificate_expires_at,
                'is_valid' => $check->is_valid,
                'is_domain_valid' => $this->health->isDomainValid(),
                'domain_status' => $this->health->domainStatus(),
                'last_checked_at' => Date::now(),
            ]);
        });
    }
}
