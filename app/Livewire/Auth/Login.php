<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Login')]
class Login extends Component
{
    #[Validate('required|email')]
    public $email = "test@example.com";

    #[Validate('required')]
    public $password = "password";

    public function login()
    {
        $this->validate();
        if(Auth::attempt(['email' => $this->email, 'password' => $this->password])){
            request()->session()->regenerate();
            $this->redirectIntended(route('app.dashboard'), navigate: true);
        }
        session()->flash('error', __('The provided credentials do not match our records.'));
        return;
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
