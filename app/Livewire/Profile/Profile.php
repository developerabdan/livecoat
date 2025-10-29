<?php

namespace App\Livewire\Profile;

use App\Services\Interfaces\Totp as TotpInterface;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profile')]
class Profile extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    public ?array $data = [];
    public ?string $qrCode = null;
    public ?string $tempSecret = null;
    public ?string $verificationCode = null;
    public bool $showRecoveryCodes = false;
    
    protected TotpInterface $totp;

    public function render()
    {
        return view('livewire.profile.profile');
    }
    public function boot(TotpInterface $totp): void
    {
        $this->totp = $totp;
    }
    
    public function mount(): void
    {
        $this->changePasswordSchema->fill();
    }
    public function changeAvatarSchema(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar')
                    ->image()
                    ->hiddenLabel()
                    ->imageEditor()
                    ->circleCropper()
                    ->directory('avatars')
                    ->disk('public')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('100')
                    ->imageResizeTargetHeight('100')
                    ->required(),
            ])
            ->statePath('data')
            ->model(Auth::user());
    }
    public function changePasswordSchema(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('current_password')
                    ->placeholder('Enter current password')
                    ->maxLength(255)
                    ->revealable()
                    ->password()
                    ->required(),
                TextInput::make('password')
                    ->placeholder('Enter new password')
                    ->maxLength(255)
                    ->revealable()
                    ->password()
                    ->required(),
                TextInput::make('password_confirmation')
                    ->placeholder('Confirm new password')
                    ->maxLength(255)
                    ->revealable()
                    ->password()
                    ->required(),
            ])
            ->statePath('data')
            ->model(Auth::user());
    }
    public function changePassword()
    {
        $data = $this->changePasswordSchema->getState();
        if(!Hash::check($data['current_password'], Auth::user()->password)) {
            Notification::make()
                ->title(__('Current password is incorrect'))
                ->warning()
                ->send();
            return;
        }
        Auth::user()->update([
            'password' => Hash::make($data['password'])
        ]);
        Notification::make()
            ->title(__('Password changed successfully'))
            ->success()
            ->send();
        $this->dispatch('close-modal',id:'changePassword');
    }
    public function changeAvatar()
    {
        $data = $this->changeAvatarSchema->getState();
        
        Auth::user()->update([
            'avatar' => $data['avatar']
        ]);
        
        Notification::make()
            ->title(__('Avatar updated successfully'))
            ->success()
            ->send();
        
        $this->dispatch('close-modal', id: 'changeAvatar');
    }
    
    /**
     * Enable 2FA - Generate QR code
     */
    public function enable2fa()
    {
        if (Auth::user()->totp_secret) {
            Notification::make()
                ->title(__('2FA is already enabled'))
                ->warning()
                ->send();
            return;
        }
        
        // Generate new secret
        $this->tempSecret = $this->totp->generateSecret();
        
        // Generate QR code
        $this->qrCode = $this->totp->getInlineQr(
            Auth::user()->email,
            $this->tempSecret,
            config('app.name', 'MyApp')
        );
    }
    
    /**
     * Verify and confirm 2FA setup
     */
    public function verify2fa()
    {
        if (!$this->verificationCode) {
            Notification::make()
                ->title(__('Please enter the verification code'))
                ->warning()
                ->send();
            return;
        }
        
        if (!$this->tempSecret) {
            Notification::make()
                ->title(__('Please enable 2FA first'))
                ->warning()
                ->send();
            return;
        }
        
        // Verify the code
        if (!$this->totp->verifyCode($this->tempSecret, $this->verificationCode)) {
            Notification::make()
                ->title(__('Invalid verification code'))
                ->danger()
                ->send();
            return;
        }
        
        // Save the secret to user
        Auth::user()->update([
            'totp_secret' => $this->tempSecret
        ]);
        
        Notification::make()
            ->title(__('2FA enabled successfully'))
            ->success()
            ->send();
        
        // Reset state
        $this->reset(['qrCode', 'tempSecret', 'verificationCode']);
        $this->dispatch('close-modal', id: 'setup2fa');
    }
    
    /**
     * Disable 2FA
     */
    public function disable2fa()
    {
        if (!Auth::user()->totp_secret) {
            Notification::make()
                ->title(__('2FA is not enabled'))
                ->warning()
                ->send();
            return;
        }
        
        Auth::user()->update([
            'totp_secret' => null
        ]);
        
        Notification::make()
            ->title(__('2FA disabled successfully'))
            ->success()
            ->send();
        
        $this->dispatch('close-modal', id: 'setup2fa');
    }
    
    /**
     * Cancel 2FA setup
     */
    public function cancel2faSetup()
    {
        $this->reset(['qrCode', 'tempSecret', 'verificationCode']);
    }
}
