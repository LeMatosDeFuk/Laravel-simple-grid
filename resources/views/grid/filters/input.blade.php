<input type="{{ $column->type }}"
       class="form-control"
       @if($column->isLazy)
            wire:model.lazy="filters.{{ $columnName }}"
       @else
           wire:model.debounce.750ms="filters.{{ $columnName }}"
       @endif
>
