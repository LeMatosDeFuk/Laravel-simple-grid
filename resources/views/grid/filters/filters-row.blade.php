@foreach($grid->getColumns() as $columnName => $column)
    @php
        $isSelected = false;
    @endphp
    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
        @if($column->type === 'select')
            @include('lematosdefuk::grid.filters.select')
        @else
            @include('lematosdefuk::grid.filters.input')
        @endif
    </th>
@endforeach

@if($grid->getActions())
    <th></th>
@endif
