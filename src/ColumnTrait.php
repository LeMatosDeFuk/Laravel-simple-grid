<?php

namespace LeMatosDeFuk;

trait ColumnTrait
{
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function setRenderer(
        callable  $renderer,
        ?callable $conditionCallback = null
    ): self
    {
        $this->renderer = new Renderer($renderer, $conditionCallback);

        return $this;
    }

    public function setSortBy($sortBy)
    {
        $sortByWithPrefix = explode('.', $sortBy);

        if (isset($sortByWithPrefix[1])) {
            $this->prefix = $sortByWithPrefix[0];
            $this->sortBy = $sortByWithPrefix[1];
        } else {
            $this->sortBy = $sortBy;
        }

        return $this;
    }

    public function setFilter(
        callable  $renderer,
        ?callable $conditionCallback = null
    ): self
    {
        $this->filter = new Filter($renderer, $conditionCallback);

        return $this;
    }

}
