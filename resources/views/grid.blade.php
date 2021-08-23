<div>
    <div class="px-4">
        @include('lematosdefuk::notifications.notification', ['message' => __('Record has been deleted')])
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    @include('lematosdefuk::grid.grid-for-page')
                    <div class="d-flex flex-row justify-content-between">
                        @yield('grid-header')
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                @include('lematosdefuk::grid.column-names')
                            </tr>
                            <tr>
                                @include('lematosdefuk::grid.filters.filters-row')
                            </tr>
                            </thead>
                            <tbody>
                            @include('lematosdefuk::grid.data-rows')
                            </tbody>
                        </table>

                        @include('lematosdefuk::grid.results-per-page')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ $grid->getModels()->links('lematosdefuk::grid.pagination') }}
</div>

@include('lematosdefuk::grid.scripts')
