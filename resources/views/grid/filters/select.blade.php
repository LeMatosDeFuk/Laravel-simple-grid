{{-- Hidden input for select2 --}}
<input type="hidden" wire:model="filters.{{ $columnName }}"
       id="{{ $columnName }}-input">

<select wire:ignore
        class="form-control custom-select" id="{{ $columnName }}-select">

    @if($filters[$columnName] !== '')
        {{--  If filter contains this column, show option. ->  ***!!!***  --}}
        @php
            $isSelected = true;
        @endphp
        <option value="{{ $filters[$columnName] }}"
                selected="selected">{{ __(property_exists($column, 'model') ? $column->model::find($filters[$columnName])->{$column->sortBy} : $filters[$columnName]) }}</option>
    @else
        {{--  show default select2 placeholder  --}}
        <option></option>
    @endif

    @if($column->data)
        {{-- If column HAS predefined data --}}
        @foreach($column->data as $value => $label)
            <option value="{{ $value }}">{{ __($label) }}</option>
        @endforeach

    @elseif(!property_exists($column, 'model'))
        {{-- If column HAS NO predefined data for select2 --}}

        @if ($isSelected)
            {{--  If filter contains this column, we will exclude this value from options ->  ***!!!***  --}}
            @foreach(DB::table($column->getPrefix())->where($columnName, '!=', $filters[$columnName])->orderBy($columnName)->pluck($columnName)->unique() as $data)
                <option value="{{ $data }}">{{ __($data) }}</option>
            @endforeach
        @else
            @foreach(DB::table($column->getPrefix())->orderBy($columnName)->pluck($columnName)->unique() as $data)
                <option value="{{ $data }}">{{ __($data) }}</option>
            @endforeach
        @endif

    @endif
</select>
