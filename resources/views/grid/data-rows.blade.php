@foreach($grid->getModels() as $model)
    <tr>
        @foreach($grid->getColumns() as $columnName => $column)
            <td class="text-center">
                @php
                    $data = property_exists($column, 'renderer') ? call_user_func($column->getRenderer()->getCallback(), $model) : $model->{$column->getColumnName()}
                @endphp

                <p class="text-xs font-weight-bold mb-0">{{ $data }}</p>
            </td>
        @endforeach

        @include('lematosdefuk::grid.actions')
    </tr>
@endforeach
