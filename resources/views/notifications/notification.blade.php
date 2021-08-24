@if ($showNotification)
    <div wire:model="showNotification"
         class="mt-3 alert alert-primary alert-dismissible fade show" role="alert">
        <span class="alert-icon text-white"><i class="fa fa-bell"></i></span>
        <span class="alert-text text-white">{{ __($message) }}</span>

        <button wire:click="$set('showNotification', false)" type="button"
                class="btn-close"
                data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif
