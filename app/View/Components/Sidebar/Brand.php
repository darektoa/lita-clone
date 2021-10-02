<?php

namespace App\View\Components\Sidebar;

use Illuminate\View\Component;

class Brand extends Component
{
    public $img;
    public $name;
    public $route;


    public function __construct($img, $name, $route)
    {
        $this->img = $img;
        $this->name = $name;
        $this->route = $route;
    }

    
    public function render()
    {
        return view('components.sidebar.brand');
    }
}
