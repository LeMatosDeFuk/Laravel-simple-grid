<?php

namespace LeMatosDeFuk;

use Livewire\Component;
use Livewire\WithPagination;

interface GridInterface
{
    public function render();

    public function createGrid();

    public function filterData();

}

/**
 * @author Matěj Černý <me@cernymatej.cz>
 */
class GridComponent extends Component
{
    use WithPagination;

    public string $sort_by          = 'model_id';
    public string $sort_dir         = 'desc';
    public int    $forPage          = 25;
    public bool   $showNotification = false;
    protected     $listeners        = [
        'showNotification' => 'showNotification'
    ];

    public function setSort($sortBy, $sortDir)
    {
        $this->sort_by  = $sortBy;
        $this->sort_dir = $sortDir;
    }

    public function showNotification()
    {
        $this->showNotification = true;
    }

}
