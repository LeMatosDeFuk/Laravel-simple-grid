<?php

namespace LeMatosDeFuk;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

interface GridInterface
{
    public function render();

    public function createGrid(): Grid;

    public function filterData(): Builder;

}

/**
 * @author Matěj Černý <me@cernymatej.cz>
 */
class GridComponent extends Component
{
    use WithPagination;

    public string $sort_by          = 'id';
    public string $sort_dir         = 'desc';
    public array  $filters          = [];
    public int    $forPage          = 25;
    public bool   $showNotification = false;
    public string $message          = 'Record has been deleted';
    protected     $queryString      = ['filters', 'sort_by', 'sort_dir'];
    protected     $listeners        = ['showNotification' => 'showNotification'];

    public function getTable(): ?string
    {
        return $this->table ?: null;
    }

    public function setSort($sortBy, $sortDir)
    {
        $this->sort_by  = $sortBy;
        $this->sort_dir = $sortDir;
    }

    public function addMessage($message)
    {
        $this->message = $message;
        $this->showNotification();
    }

    public function showNotification()
    {
        $this->showNotification = true;
    }

    public function getSortDir(): string
    {
        return $this->sort_dir;
    }

    public function getSortBy(): string
    {
        return $this->sort_by;
    }

}
