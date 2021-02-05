<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CheckDetails extends Component
{
    public $check;

    public bool $shown = false;

    public function mount($check)
    {
        $this->check = $check;
    }
}
