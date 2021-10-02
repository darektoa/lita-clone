<?php

namespace App\View\Components\Sidebar;

use Illuminate\View\Component;

class NavItem extends Component
{
    public $active;
    public $icon;
    public $name;
    public $route;


    public function __construct($active, $icon, $name, $route)
    {
        $this->active   = $this->activeClassName($active);
        $this->icon     = $icon;
        $this->name     = $name;
        $this->route    = $route;
    }

    
    public function render()
    {
        return view('components.sidebar.nav-item');
    }


    public function activeClassName($active)
    {
        return $this->active = $active ? 'active' : '';
    }
}
