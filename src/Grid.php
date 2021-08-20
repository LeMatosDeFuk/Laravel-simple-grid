<?php

namespace LeMatosDeFuk\LaravelSimpleGrid;

use Illuminate\Support\Collection;

/**
 * @author Matěj Černý <me@cernymatej.cz>
 */
class Grid
{
    public mixed $columns;
    public mixed $models;
    public bool  $actions = false;

    public function getModels()
    {
        return $this->models;
    }

    public function setModels($component): void
    {
        $this->models = $this->filterModels($component);
    }

    public function filterModels($component)
    {
        $sortDir = $component->sort_dir;
        $sortBy  = $this->getColumns()->get($component->sort_by)['relationSort'] ?? $this->getColumns()->get($component->sort_by)['name'];
        $sortBy  = $sortBy === 'model_id' ? 'id' : $sortBy;

        $models = $component->filterData()
                            ->orderBy($sortBy, $sortDir)
                            ->paginate($component->forPage);

        $component->emit('render-select');

        return $models;
    }

    public function getColumns(): Collection
    {
        return collect($this->columns);
    }

    public function addActions(): void
    {
        $this->actions = true;
    }

    public function getActions(): bool
    {
        return $this->actions;
    }

    public function addColumnDate(
        string $label,
        string $name,
        string $function = null,
        mixed  $functionArguments = null,
        bool   $isLazy = true,
        string $type = 'date'
    ): void
    {
        $this->columns[$name] = [
            'label'             => $label,
            'name'              => $name,
            'function'          => $function,
            'functionArguments' => $functionArguments,
            'isLazy'            => $isLazy,
            'type'              => $type
        ];
    }

    public function addSelect(
        string      $label,
        string      $name,
        string      $function = null,
        array       $data = [],
        mixed       $functionArguments = null,
        bool        $isLazy = false,
        string      $type = 'select',
        bool        $search = true,
        null|string $model = null,
        string      $relationSort = null,
    ): void
    {
        $this->columns[$name] = [
            'label'             => $label,
            'name'              => $name,
            'function'          => $function,
            'functionArguments' => $functionArguments,
            'isLazy'            => $isLazy,
            'type'              => $type,
            'data'              => $data,
            'search'            => $search,
            'model'             => $model,
            'relationSort'      => $relationSort
        ];
    }

    public function addColumn(
        string $label,
        string $name,
        string $function = null,
        mixed  $functionArguments = null,
        bool   $isLazy = false,
        string $type = 'text'
    ): void
    {
        $this->columns[$name] = [
            'label'             => __($label),
            'name'              => $name,
            'function'          => $function,
            'functionArguments' => $functionArguments,
            'isLazy'            => $isLazy,
            'type'              => $type,
        ];
    }

}