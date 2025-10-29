<?php

namespace App\Livewire\Settings\SystemSetting;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('System Settings')]
class SystemSetting extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    public ?array $data = [];
    public function render()
    {
        return view('livewire.settings.system-setting.system-setting');
    }

    public function mount(): void
    {
        $this->appVersion->fill([
            'app_version' => setting('app_version.version')
        ]);
        $this->enableTotp->fill();
        $this->appIcon->fill([
            'app_icon' => setting('app_icon.path')
        ]);
        $this->loginLogo->fill([
            'login_logo_light' => setting('login_logo.path_light'),
            'login_logo_dark' => setting('login_logo.path_dark')
        ]);
        $this->appLogo->fill([
            'app_logo_light' => setting('app_logo.path_light'),
            'app_logo_dark' => setting('app_logo.path_dark')
        ]);
        $this->enableGoogleRecaptcha->fill();
    }
    public function enableTotp(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('enable_totp')
                    ->hiddenLabel()
                // ...
            ])
            ->statePath('data');
    }
    public function appIcon(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('app_icon')
                    ->hiddenLabel()
                    ->image()
                    ->imageEditor()
                    ->directory('systems')
                    ->disk('public')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->required(),
            ])
            ->statePath('data');
    }
    public function loginLogo(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('login_logo_light')
                    ->label('Light')
                    ->image()
                    ->imageEditor()
                    ->directory('systems')
                    ->disk('public')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->required(),
                
                FileUpload::make('login_logo_dark')
                    ->label('Dark')
                    ->image()
                    ->imageEditor()
                    ->directory('systems')
                    ->disk('public')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->required(),
                // ...
            ])
            ->statePath('data');
    }

    public function appLogo(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('app_logo_light')
                    ->label('Light')
                    ->image()
                    ->imageEditor()
                    ->directory('systems')
                    ->disk('public')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->required(),
                FileUpload::make('app_logo_dark')
                    ->label('Dark')
                    ->image()
                    ->imageEditor()
                    ->directory('systems')
                    ->disk('public')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->required(),
                // ...
            ])
            ->statePath('data');
    }
    public function appVersion(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('app_version')
                    ->placeholder('eg: 1.0.0')
                    ->required()
                    ->extraInputAttributes(['class' => 'w-20 text-center'])
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $state) {
                        Setting::query()
                            ->updateOrCreate([
                                'key' => 'app_version'
                            ], [
                                'value' => ['version' => $state]
                            ]);
                        Notification::make()
                            ->title(__('App version updated successfully'))
                            ->success()
                            ->send();
                    })
                    ->hiddenLabel()
            ])
            ->statePath('data');
    }
    public function appName(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('app_name')
                    ->placeholder('eg: Livecoat')
                    ->required()    
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $state) {
                        Setting::query()
                            ->updateOrCreate([
                                'key' => 'app_name'
                            ], [
                                'value' => ['name' => $state]
                            ]);
                        Notification::make()
                            ->title(__('App name updated successfully'))
                            ->success()
                            ->send();
                    })
                    ->hiddenLabel()
            ])
            ->statePath('data');
    }
    public function updateAppIcon()
    {
        $data = $this->appIcon->getState();
        Setting::query()
                ->updateOrCreate([
                    'key' => 'app_icon'
                ], [
                    'value' => [
                        'path' => $data['app_icon']
                    ]
                ]);
        Notification::make()
            ->title(__('App icon updated successfully'))
            ->success()
            ->send();
        $this->dispatch('close-modal', id: 'app-icon-modal');
    }
    public function updateLoginLogo()
    {
        $data = $this->loginLogo->getState();
        Setting::query()
            ->updateOrCreate([
                'key' => 'login_logo'
            ], [
                'value' => [
                    'path_light' => $data['login_logo_light'],
                    'path_dark' => $data['login_logo_dark']
                ]
            ]);
        Notification::make()
            ->title(__('Login logo updated successfully'))
            ->success()
            ->send();
        $this->dispatch('close-modal', id: 'login-logo-modal');
    }
    public function clearCache()
    {
        Cache::forget('settings');
        Notification::make()
            ->title(__('Cache cleared successfully'))
            ->success()
            ->send();
        $this->redirect(route('app.settings.system-setting'));
    }
    public function updateAppLogo()
    {
        $data = $this->appLogo->getState();
        Setting::query()
            ->updateOrCreate([
                'key' => 'app_logo'
            ], [
                'value' => [
                    'path_light' => $data['app_logo_light'],
                    'path_dark' => $data['app_logo_dark']
                ]
            ]);
        Notification::make()
            ->title(__('App logo updated successfully'))
            ->success()
            ->send();
        $this->dispatch('close-modal', id: 'app-logo-modal');
    }
    public function enableGoogleRecaptcha(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('enable_google_recaptcha')
                    ->hiddenLabel()
                // ...
            ])
            ->statePath('data');
    }
}
