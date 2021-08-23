@php
    $oppositeDir = $sort_dir === 'desc' ? 'asc' : 'desc';
@endphp

@foreach($grid->getColumns() as $columnName => $column)
    @php
        $isActive = $sort_by === $columnName;
    @endphp

    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
        <a href="javascript:"
           wire:click="setSort('{{ $columnName }}', '{{ $isActive && $oppositeDir }}')"
            @class(['text-primary' => $sort_by === $columnName])
        >
            @lang($column->label)

            @include('lematosdefuk::grid.sort-icons')
        </a>
    </th>

@endforeach

@if($grid->getActions())
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
        @lang('Actions')
    </th>
@endif
