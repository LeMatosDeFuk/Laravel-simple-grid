<?php

namespace LeMatosDeFuk;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * @author Matěj Černý <me@cernymatej.cz>
 */
class Grid
{
    public mixed $columns;
    public mixed $component;
    public mixed $models;
    public bool  $actions = false;

    /**
     * @param mixed $component
     */
    public function __construct(mixed $component)
    {
        $this->setComponent($component);
    }

    public function getModels(): mixed
    {
        return $this->models;
    }

    public function setModels(): void
    {
        $this->models = $this->prepareModels();
    }

    public function prepareModels(): mixed
    {
        $component = $this->getComponent();
        $this->setColumnProperties();
        $query = $this->filterQuery();

        $component->emit('render-select');

        return $query;
    }

    public function getComponent(): GridComponent
    {
        return $this->component;
    }

    public function setComponent($component)
    {
        $this->component = $component;
    }

    public function setColumnProperties()
    {
        $component = $this->getComponent();

        foreach ($this->getColumns() as $name => $column) {
            if (!isset($component->filters[$name])) {
                $component->filters[$name] = '';
            }
        }
    }

    public function getColumns(): Collection
    {
        return collect($this->columns);
    }

    public function filterQuery()
    {
        $component = $this->getComponent();
        $sortDir   = $component->getSortDir();
        $sortBy    = $this->getSortBy();
        $query     = $component->filterData();

        foreach ($this->getColumns() as $name => $column) {
            $filterValue = $component->filters[$name];
            if (property_exists($column, 'filter')) {
                $query = $query->when($filterValue, fn ($query, $value) => call_user_func($column->getFilter()->getCallback(), $query, $value));
            } else {
                $columnName = $column->getPrefix() . '.' . $column->getColumnName();

                if ($column->type === 'date') {
                    $query = $this->filterDate($query, $filterValue, $columnName);
                } else {
                    $operator = $column->getOperator();

                    if ($operator === 'like') {
                        $query = $this->filterLike($query, $filterValue, $columnName);
                    } else {
                        $query = $this->filterOther($query, $filterValue, $columnName, $column, $operator);
                    }
                }
            }
        }

        $query = $query->orderBy($sortBy, $sortDir)
                       ->paginate($component->forPage);

        return $query;
    }

    public function getSortBy()
    {
        return $this->getColumns()->get($this->getComponent()->getSortBy())->getSortColumnName($this->getComponent());
    }

    public function filterDate(Builder $query, $filterValue, $columnName): Builder
    {
        if ($filterValue !== "") {
            $query = $query->whereDate($columnName, Carbon::parse($filterValue));
        }

        return $query;
    }

    public function filterLike(Builder $query, $filterValue, $columnName): Builder
    {
        if ($filterValue !== "") {
            $query = $query->where($columnName, 'like', '%' . $filterValue . '%');
        }

        return $query;
    }

    public function filterOther(Builder $query, $filterValue, $columnName, $column, $operator): Builder
    {
        if ($filterValue !== "") {
            $query = $query->where($columnName, $operator, $column->formatValue($filterValue));
        }

        return $query;
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
        string      $label,
        string|null $name = null,
        string      $type = 'date'
    ): Column
    {
        $name = $this->getName($label, $name);

        $column = new Column([
                                 'label'  => $label,
                                 'name'   => $name,
                                 'type'   => $type,
                                 'isLazy' => true,
                                 'prefix' => $this->getComponent()->getTable(),
                             ]);

        $this->columns[$name] = $column;

        return $column;
    }

    public function getName($label, $name)
    {
        return $name ?: strtolower($label);
    }

    public function addSelect(
        string      $label,
        string|null $name = null,
        array       $data = [],
        bool        $search = true,
        string      $dataType = 'string',
        string      $operator = '=',
        bool        $isLazy = false,
    ): Column
    {
        $name = $this->getName($label, $name);

        $column = new Column([
                                 'label'    => $label,
                                 'name'     => $name,
                                 'data'     => $data,
                                 'search'   => $search,
                                 'isLazy'   => $isLazy,
                                 'operator' => $operator,
                                 'type'     => 'select',
                                 'dataType' => $dataType,
                                 'prefix'   => $this->getComponent()->getTable(),
                             ]);

        $this->columns[$name] = $column;

        return $column;
    }

    public function addColumn(
        string      $label,
        string|null $name = null,
        string      $operator = 'like',
        bool        $isLazy = false,
        string      $type = 'text'
    ): Column
    {
        $name = $this->getName($label, $name);

        $column = new Column([
                                 'label'    => __($label),
                                 'name'     => $name,
                                 'isLazy'   => $isLazy,
                                 'operator' => $operator,
                                 'type'     => $type,
                                 'prefix'   => $this->getComponent()->getTable(),
                             ]);

        $this->columns[$name] = $column;

        return $column;
    }

}
