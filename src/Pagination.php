<?php

namespace LeMatosDeFuk;

use Illuminate\View\Component;
use Illuminate\View\View;

class Pagination extends Component
{
    public $models;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($models)
    {
        $this->models = $models;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('lematosdefuk-simple-grid::pagination');
    }
}
