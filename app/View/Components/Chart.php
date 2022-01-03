<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Chart extends Component
{
    public $canvasId;
    public $id;
    public $title;

    public function __construct($canvasId, $id=null, $title='Chart')
    {
        $this->canvasId = $canvasId;
        $this->id       = $id;
        $this->title    = $title;
    }


    public function render()
    {
        return view('components.chart');
    }
}
