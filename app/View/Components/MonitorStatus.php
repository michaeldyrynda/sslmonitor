<?php

namespace App\View\Components;

use App\Monitor;
use Illuminate\View\Component;

class MonitorStatus extends Component
{
    public Monitor $monitor;

    public string $inner = 'bg-green-400';

    public string $outer = 'bg-green-100';

    public string $tooltip = 'Certificate is healthy';

    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;

        $this->classifyStatus();
    }

    public function render()
    {
        return view('components.monitor-status');
    }

    protected function classifyStatus()
    {
        if ($this->monitor->is_expired || $this->monitor->is_invalid) {
            $this->outer = 'bg-red-100';
            $this->inner = 'bg-red-400';
            $this->tooltip = $this->monitor->is_expired 
                ? 'Certificate expired' 
                : 'Certificate is invalid';
        } 

        if ($this->monitor->should_renew_domain || $this->monitor->is_expiring) {
            $this->outer = 'bg-yellow-100';
            $this->inner = 'bg-yellow-400';
            $this->tooltip = $this->monitor->should_renew_domain
                ? 'Domain is due for renewal'
                : 'Certificate is expiring';
        }
    }
}
