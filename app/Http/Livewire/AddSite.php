<?php

namespace App\Http\Livewire;

use App\Jobs\CheckSite;
use App\Monitor;
use Livewire\Component;

class AddSite extends Component
{
    public $site;

    public bool $added = false;

    protected function rules()
    {
        return [
            'site' => [
                'required', 
                'unique:monitors',
                function ($attribute, $value, $fail) {
                    if (! filter_var(gethostbyname($value), FILTER_VALIDATE_IP)) {
                        $fail('Please enter a valid domain to monitor');
                    }
                },
            ],
        ];
    }

    public function addMonitor()
    {
        $this->validate();

        CheckSite::dispatch(Monitor::create([
            'site' => $this->site,
        ]));

        $this->added = true;

        $this->site = null;

        $this->emitTo('site-monitors', 'siteAdded');
    }
}
