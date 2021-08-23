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
