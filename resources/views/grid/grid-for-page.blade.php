<div class="mb-3">
    <div style="width: 225px;">
        <label for="for-page" class="m-0">
            @lang('Entries Per Page')
            <select name="hop" class="form-control bg-white" id="for-page" wire:model="forPage" readonly="false">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </label>
    </div>
</div>
