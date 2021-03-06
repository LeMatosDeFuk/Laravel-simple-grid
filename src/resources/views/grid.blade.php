<div>
    @include('lematosdefuk::grid-for-page')

    <div class="px-4">
        @include('lematosdefuk::notification', ['message' => __('Record has been deleted')])
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        @yield('grid-header')
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                @foreach($grid->getColumns() as $columnName => $column)
                                    @php
                                        $isActive = $sort_by === $columnName
                                    @endphp
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        <a href="javascript:"
                                           wire:click="setSort('{{ $columnName }}', '{{ $isActive && $sort_dir === 'desc' ? 'asc' : 'desc' }}')"
                                            @class(['text-primary' => $sort_by === $columnName])
                                        >
                                            @lang($column->label)
                                            @if($isActive)
                                                @if($sort_dir === 'desc')
                                                    <i class="fas fa-sort-down"></i>
                                                @else
                                                    <i class="fas fa-sort-up"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                @endforeach
                                @if($grid->getActions())
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        @lang('Actions')
                                    </th>
                                @endif
                            </tr>
                            <tr>
                                @foreach($grid->getColumns() as $columnName => $column)
                                    @php
                                        $isSelected = false;
                                    @endphp
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        @if($column->type === 'select')
                                            <input type="hidden" wire:model="filters.{{ $columnName }}"
                                                   id="{{ $columnName }}-input">
                                            <select wire:ignore
                                                    class="form-control custom-select" id="{{ $columnName }}-select">
                                                @if($filters[$columnName] !== '')
                                                    @php
                                                        $isSelected = true;
                                                    @endphp
                                                    <option value="{{ $filters[$columnName] }}"
                                                            selected="selected">{{ __(property_exists($column, 'model') ? $column->model::find($filters[$columnName])->{$column->sortBy} : $filters[$columnName]) }}</option>
                                                @else
                                                    <option></option>
                                                @endif

                                                @if($column->data)
                                                    @foreach($column->data as $value => $label)
                                                        <option value="{{ $value }}">{{ __($label) }}</option>
                                                    @endforeach
                                                @elseif(!property_exists($column, 'model'))
                                                    @if ($isSelected)
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
                                        @else
                                            <input type="{{ $column->type }}" class="form-control"
                                                   @if($column->isLazy) wire:model.lazy="filters.{{ $columnName }}"
                                                   @else wire:model.debounce.750ms="filters.{{ $columnName }}" @endif>
                                        @endif
                                    </th>
                                @endforeach
                                @if($grid->getActions())
                                    <th></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
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
                                    @if($grid->getActions())
                                        <td class="text-center">
                                            <a href="{{ route(strtolower(class_basename($model)) . '.detail', $model) }}"
                                               class="mx-1"
                                               data-bs-toggle="tooltip"
                                               data-bs-original-title="@lang('Edit')">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <a class="mx-1"
                                               wire:click="$emit('showModal', 'lematosdefuk::delete', {{ $model->getId() }}, '{{ urlencode($model::class) }}')"
                                               data-bs-toggle="modal" data-bs-target="#modal-notification">
                                                <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <p class="mx-4 my-2 font-weight-light">
                            {{ $forPage * $page - $forPage + 1 }} -
                            {{ $forPage * $page > $grid->getModels()->total() ? $grid->getModels()->total() : $forPage * $page }}

                            @lang('results from') {{ $grid->getModels()->total() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ $grid->getModels()->links('lematosdefuk::pagination') }}
</div>

@push('scripts')
    <script>
        function selectsInit() {
            @foreach($grid->getColumns()->where('type', 'select') as $column)
            @if($column->search)
            select2Init('{{ $column->name }}-select', @json($column->model));
            @else
            select2InitNoSearch('{{ $column->name }}-select');
            @endif
            @endforeach
        }

        selectsInit()

        Livewire.on('render-select', function () {
                selectsInit()
            }
        )
    </script>
@endpush
