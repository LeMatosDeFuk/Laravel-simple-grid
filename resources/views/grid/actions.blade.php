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
