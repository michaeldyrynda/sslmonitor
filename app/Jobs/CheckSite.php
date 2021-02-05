<?php

namespace App\Jobs;

use App\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;
use Spatie\SslCertificate\SslCertificate;

class CheckSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Monitor $monitor;

    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

    public function handle()
    {
        tap(SslCertificate::createForHostName($this->monitor->site), function ($certificate) {
            $check = $this->monitor->createCheckFromCertificate($certificate);

            if (! $check->is_valid && $this->monitor->is_valid) {
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
                'last_checked_at' => Date::now(),
            ]);
        });
    }
}
