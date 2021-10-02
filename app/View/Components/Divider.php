<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Divider extends Component
{
    public $mb;
    public $mt;


    public function __construct($mb, $mt)
    {
        $this->mb = $mb ?? 0;
        $this->mt = $mt ?? 0;
    }

    
    public function render()
    {
        return view('components.divider');
    }
}
