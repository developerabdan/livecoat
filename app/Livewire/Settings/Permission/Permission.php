<?php

namespace App\Livewire\Settings\Permission;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Permission')]
class Permission extends Component
{
    public function render()
    {
        return view('livewire.settings.permission.permission');
    }
}
