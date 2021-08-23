@if($isActive)
    @if($sort_dir === 'desc')
        <i class="fas fa-sort-down"></i>
    @else
        <i class="fas fa-sort-up"></i>
    @endif
@else
    <i class="fas fa-sort"></i>
@endif
