<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\Interfaces\Totp as TotpInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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
    
    public $totpCode = '';
    public bool $requires2fa = false;
    public ?string $pendingUserId = null;
    public $recaptchaToken = '';
    
    protected TotpInterface $totp;

    public function boot(TotpInterface $totp): void
    {
        $this->totp = $totp;
    }
    
    public function login()
    {
        $this->validate();
        
        // Verify reCAPTCHA if enabled
        if (setting('google_recaptcha_v2.enabled')) {
            if (!$this->verifyRecaptcha()) {
                $this->recaptchaToken = '';
                $this->dispatch('resetRecaptcha');
                session()->flash('error', __('Please complete the reCAPTCHA verification.'));
                return;
            }
        }
        
        $user = User::where('email', $this->email)->first();
        if (!$user || !Hash::check($this->password, $user->password)) {
            $this->recaptchaToken = '';
            $this->dispatch('resetRecaptcha');
            session()->flash('error', __('The provided credentials do not match our records.'));
            return;
        }
        
        if (setting('enable_totp.enabled') && $user->totp_secret) {
            $this->pendingUserId = $user->id;
            $this->requires2fa = true;
            
            return;
        }

        Auth::loginUsingId($user->id, true);
        
        request()->session()->regenerate();
        $this->reset(['recaptchaToken']);
        $this->redirectIntended(route('app.dashboard'), navigate: true);
    }
    
    public function verify2fa()
    {
        if (!$this->totpCode) {
            session()->flash('error', __('Please enter the verification code'));
            return;
        }
        
        if (!$this->pendingUserId) {
            session()->flash('error', __('Session expired. Please login again.'));
            $this->reset(['requires2fa', 'pendingUserId', 'totpCode']);
            return;
        }
        
        $user = User::find($this->pendingUserId);
        
        if (!$user || !$user->totp_secret) {
            session()->flash('error', __('Invalid session. Please login again.'));
            $this->reset(['requires2fa', 'pendingUserId', 'totpCode']);
            return;
        }
        
        if (!$this->totp->verifyCode($user->totp_secret, $this->totpCode)) {
            session()->flash('error', __('Invalid verification code. Please try again.'));
            $this->totpCode = '';
            return;
        }
        
        Auth::loginUsingId($user->id, true);
        request()->session()->regenerate();
        
        $this->reset(['requires2fa', 'pendingUserId', 'totpCode', 'recaptchaToken']);
        
        $this->redirectIntended(route('app.dashboard'), navigate: true);
    }
    
    public function cancelVerification()
    {
        $this->reset(['requires2fa', 'pendingUserId', 'totpCode', 'email', 'password', 'recaptchaToken']);
        $this->dispatch('resetRecaptcha');
        session()->flash('info', __('Login cancelled. Please try again.'));
    }
    
    protected function verifyRecaptcha(): bool
    {
        if (empty($this->recaptchaToken)) {
            return false;
        }
        
        $secretKey = setting('google_recaptcha_v2.secret_key');
        
        if (empty($secretKey)) {
            return false;
        }
        
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $this->recaptchaToken,
                'remoteip' => request()->ip()
            ]);
            
            $result = $response->json();
            
            return isset($result['success']) && $result['success'] === true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
