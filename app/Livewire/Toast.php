<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Toast extends Component
{
    public $notifications = [];
    public $nextId = 1;

    #[On('notify')]
    public function addNotification($message, $type = 'success')
    {
        $this->notifications[] = [
            'id' => $this->nextId++,
            'message' => $message,
            'type' => $type,
        ];
    }

    public function removeNotification($id)
    {
        $this->notifications = array_filter(
            $this->notifications,
            fn($notification) => $notification['id'] !== $id
        );
    }

    public function render()
    {
        return view('livewire.toast');
    }
}
