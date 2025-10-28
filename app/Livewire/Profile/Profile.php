<?php

namespace App\Livewire\Profile;

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

    public function render()
    {
        return view('livewire.profile.profile');
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
}
