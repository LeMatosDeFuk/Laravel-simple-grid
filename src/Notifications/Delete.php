<?php

namespace LeMatosDeFuk\Notifications;

use Livewire\Component;

class Delete extends Component
{

    public int    $modelId;
    public string $modelClass;

    public function mount($id, $model): void
    {
        $this->modelId    = $id;
        $this->modelClass = urldecode($model);
    }

    public function delete(): void
    {
        $model = $this->modelClass::find($this->modelId);
        $model->delete();

        $this->emit('showNotification');
    }

    public function render()
    {
        return view('lematosdefuk-simple-grid::notifications.delete');
    }

}
