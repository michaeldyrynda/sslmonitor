<?php

namespace App\Console\Commands;

use App\Jobs\CheckSite;
use App\Monitor;
use Illuminate\Console\Command;

class CheckMonitors extends Command
{
    protected $signature = 'check:monitors {site? : Check a specific monitor only}';

    protected $description = 'Process all configured monitors.';

    public function handle()
    {
        Monitor::query()
            ->when($this->argument('site'), fn ($q) => $q->where('site', $this->argument('site')))
            ->each(fn ($monitor) => CheckSite::dispatch($monitor));

        return 0;
    }
}
