<?php

namespace LeMatosDeFuk;

class Column
{
    use ColumnTrait;

    public function __construct(array $data)
    {
        foreach ($data as $name => $value) {
            $this->{$name} = $value;
        }
    }

    public function getOperator()
    {
        return $this->operator;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function formatValue($value)
    {
        $dataType = property_exists($this, 'dataType') ? $this->dataType : 'string';

        if ($value === 'false') {
            return false;
        }

        if ($value === 'true') {
            return true;
        }

        $value = match ($dataType) {
            'bool', 'boolean' => (bool)$value,
            'float' => (float)$value,
            'int', 'integer' => (int)$value,
            default => (string)$value,
        };

        return $value;
    }

    public function getFilter(): Filter
    {
        return $this->filter;
    }

    public function getRenderer(): Renderer
    {
        return $this->renderer;
    }

    public function getSortColumnName($component)
    {
        return property_exists($this, 'sortBy') ? $this->sortBy : $this->getColumnName();
    }

    public function getColumnName()
    {
        return $this->name === 'id' ? 'id' : $this->name;
    }

}
