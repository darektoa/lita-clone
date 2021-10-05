<?php

namespace App\View\Components\Sidebar;

use Illuminate\View\Component;

class NavCollapseItem extends Component
{
    public $active;
    public $icon;
    public $name;
    public $routes;


    public function __construct($active, $icon, $name, $routes)
    {
        $this->active   = $this->activeClassName($active);
        $this->icon     = $icon;
        $this->name     = $name;
        $this->routes   = json_decode($routes);
    }


    public function render()
    {
        return view('components.sidebar.nav-collapse-item');
    }


    public function activeClassName($active)
    {
        return $this->active = $active ? 'active' : '';
    }
}
