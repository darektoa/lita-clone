<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class InfoBox extends Component
{
    public $color;
    public $icon;
    public $title;
    public $value;

    
    public function __construct($color, $icon, $title, $value)
    {
        $this->color = $color;
        $this->icon  = $icon;
        $this->title = $title;
        $this->value = $value;
    }


    public function render()
    {
        return view('components.dashboard.info-box');
    }
}
