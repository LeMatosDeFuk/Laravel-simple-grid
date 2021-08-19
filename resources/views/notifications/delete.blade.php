<div>
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"
                    id="modal-title-notification">@lang('Your attention is required')</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3 text-center">
                    <i class="ni ni-bell-55 ni-3x"></i>
                    <h4 class="text-gradient text-danger mt-4">@lang('You should read this!')</h4>
                    <p>
                        @lang('This cannot be undone.') <br>
                        @lang('Are you sure you want to delete this record?')
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" wire:click="delete()"
                        class="btn btn-danger" data-bs-dismiss="modal">
                    @lang('Yes, delete it.')
                </button>
                <button type="button" class="btn ml-auto" data-bs-dismiss="modal">
                    @lang('No, I want to leave.')
                </button>
            </div>
        </div>
    </div>
</div>

