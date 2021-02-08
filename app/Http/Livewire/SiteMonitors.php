<?php

namespace App\Http\Livewire;

use App\Monitor;
use Livewire\Component;
use Livewire\WithPagination;

class SiteMonitors extends Component
{
    use WithPagination;

    protected $listeners = [
        'siteAdded' => 'updateMonitors',
    ];

    public function render()
    {
        return view('livewire.site-monitors', [
            'monitors' => Monitor::with('latestCheck')->oldest('certificate_expires_at')->paginate(10),
        ]);
    }

    public function updateMonitors()
    {
        $this->resetPage();
    }

    public function deleteMonitor(Monitor $monitor)
    {
        $monitor->delete();

        $this->updateMonitors();
    }
}
