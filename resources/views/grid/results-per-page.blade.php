<p class="mx-4 my-2 font-weight-light">
    {{ $forPage * $page - $forPage + 1 }} -
    {{ $forPage * $page > $grid->getModels()->total() ? $grid->getModels()->total() : $forPage * $page }}

    @lang('results from') {{ $grid->getModels()->total() }}
</p>
