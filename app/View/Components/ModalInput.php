<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalInput extends Component
{   
    public $action;
    public $id;
    public $inputs;
    public $method;
    public $title;

    public function __construct($action, $id, $inputs, $method, $title)
    {
        $this->action   = $action;
        $this->id       = $id;
        $this->inputs   = json_decode($inputs);
        $this->method   = $method;
        $this->title    = $title;
    }


    public function render()
    {
        return view('components.modal-input');
    }
}
