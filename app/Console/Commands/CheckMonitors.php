<?php

namespace App\Console\Commands;

use App\Jobs\CheckSite;
use App\Monitor;
use Illuminate\Console\Command;

class CheckMonitors extends Command
{
    protected $signature = 'check:monitors';

    protected $description = 'Process all configured monitors.';

    public function handle()
    {
        Monitor::each(fn ($monitor) => CheckSite::dispatch($monitor));

        return 0;
    }
}
