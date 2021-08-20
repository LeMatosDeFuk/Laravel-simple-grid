<?php

namespace LeMatosDeFuk;

use Illuminate\Support\Collection;

/**
 * @author Matěj Černý <me@cernymatej.cz>
 */
class Grid
{
    public mixed $columns;
    public mixed $models;
    public bool  $actions = false;

    /**
     * @return mixed
     */
    public function getModels(): mixed
    {
        return $this->models;
    }

    /**
     * @param $component
     */
    public function setModels($component): void
    {
        $this->models = $this->filterModels($component);
    }

    /**
     * @param $component
     *
     * @return mixed
     */
    public function filterModels($component): mixed
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

    /**
     * @return Collection
     */
    public function getColumns(): Collection
    {
        return collect($this->columns);
    }

    /**
     *
     */
    public function addActions(): void
    {
        $this->actions = true;
    }

    /**
     * @return bool
     */
    public function getActions(): bool
    {
        return $this->actions;
    }

    /**
     * @param string      $label - Column name shown to user
     * @param string      $name - name of attribute from database
     * @param string|null $function - you can return row value by specifying function, e.g. 'getDateCreated' with call $user->getDateCreated()
     * @param mixed|null  $functionArguments - if you specify function, this variable is passed to that function, e.g. 'd.m.Y' will be called as $user->getDateCreated('d.m.Y')
     * @param bool        $isLazy - this argument is used mainly for datepicker, it specifies if wire.model is lazy or not
     * @param string      $type - type of column ('date')
     */
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

    /**
     * @param string      $label - Column name shown to user
     * @param string      $name - name of attribute from database
     * @param string|null $function - you can return row value by specifying function, e.g. 'getRoleName' with call $user->getRoleName()
     * @param array       $data - to this argument you can pass data which are shown in select as options
     * @param mixed|null  $functionArguments - if you specify function, this variable is passed to that function, e.g. 'random' will be called as $user->getRoleName('random')
     * @param bool        $isLazy - this argument is used mainly for datepicker, it specifies if wire.model is lazy or not
     * @param string      $type - type of column ('text', 'number', 'date')
     * @param bool        $search - specify, if you want search input or not
     * @param string|null $model - if it's relationship column, specify which model is related, e.g. Role::class
     * @param string|null $relationSort
     */
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

    /**
     * @param string      $label - Column name shown to user
     * @param string      $name - name of attribute from database
     * @param string|null $function - you can return row value by specifying function, e.g. 'getRoleName' with call $user->getRoleName()
     * @param mixed|null  $functionArguments - if you specify function, this variable is passed to that function, e.g. 'random' will be called as $user->getRoleName('random')
     * @param bool        $isLazy - this argument is used mainly for datepicker, it specifies if wire.model is lazy or not
     * @param string      $type - type of column ('text', 'number', 'date')
     */
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