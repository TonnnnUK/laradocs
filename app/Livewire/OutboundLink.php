<?php

namespace App\Livewire;

use Livewire\Component;

class OutboundLink extends Component
{

    public $link; 

    public function render()
    {
        return view('livewire.outbound-link');
    }

    public function go(){
        // dd('the link is',$this->link);
        return redirect($this->link);
    }
}
