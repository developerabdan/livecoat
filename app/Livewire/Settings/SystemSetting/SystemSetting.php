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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('System Settings')]
class SystemSetting extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    public ?array $dataAppVersion = [];
    public ?array $dataEnableTotp = [];
    public ?array $dataEnableGoogleRecaptcha = [];
    public ?array $dataAppIcon = [];
    public ?array $dataAppName = [];
    public ?array $dataLoginLogo = [];
    public ?array $dataAppLogo = [];
    public ?array $dataSetupGoogleRecaptcha = [];
    public function render()
    {
        return view('livewire.settings.system-setting.system-setting');
    }

    public function mount(): void
    {
        $this->appVersion->fill([
            'app_version' => setting('app_version.version')
        ]);
        $this->appName->fill([
            'app_name' => setting('app_name.name')
        ]);
        $this->enableTotp->fill([
            'enable_totp' => setting('enable_totp.enabled')
        ]);
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
        $this->enableGoogleRecaptcha->fill(
            [
                'enable_google_recaptcha' => setting('google_recaptcha_v2.enabled')
            ]
        );
        $this->setupGoogleRecaptcha->fill(
            [
                'secret_key' => setting('google_recaptcha_v2.secret_key'),
                'site_key' => setting('google_recaptcha_v2.site_key')
            ]
        );
    }
    public function enableTotp(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('enable_totp')
                    ->disabled(Auth::user()->cannot('Apply System Settings'))
                    ->hiddenLabel()
                    ->live()
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        $this->authorize('Apply System Settings');
                        Setting::query()
                            ->updateOrCreate([
                                'key' => 'enable_totp'
                            ], [
                                'value' => ['enabled' => $state]
                            ]);
                        $this->doClearCache();
                        Notification::make()
                            ->title(__('Enable TOTP updated successfully'))
                            ->success()
                            ->send();
                    })
            ])
            ->statePath('dataEnableTotp');
    }
    public function appIcon(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('app_icon')
                    ->disabled(Auth::user()->cannot('Apply System Settings'))
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
            ->statePath('dataAppIcon');
    }
    public function loginLogo(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('login_logo_light')
                    ->disabled(Auth::user()->cannot('Apply System Settings'))
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
                    ->disabled(Auth::user()->cannot('Apply System Settings'))
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
            ->statePath('dataLoginLogo');
    }

    public function appLogo(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('app_logo_light')
                    ->disabled(Auth::user()->cannot('Apply System Settings'))
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
                    ->disabled(Auth::user()->cannot('Apply System Settings'))
                    ->label('Dark')
                    ->imageEditor()
                    ->directory('systems')
                    ->disk('public')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->required(),
                // ...
            ])
            ->statePath('dataAppLogo');
    }
    public function appVersion(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('app_version')
                    ->disabled(Auth::user()->cannot('Apply System Settings'))
                    ->placeholder('eg: 1.0.0')
                    ->required()
                    ->extraInputAttributes(['class' => 'w-20 text-center'])
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $state) {
                        $this->authorize('Apply System Settings');
                        Setting::query()
                            ->updateOrCreate([
                                'key' => 'app_version'
                            ], [
                                'value' => ['version' => $state]
                            ]);
                        $this->doClearCache();
                        Notification::make()
                            ->title(__('App version updated successfully'))
                            ->success()
                            ->send();
                    })
                    ->hiddenLabel()
            ])
            ->statePath('dataAppVersion');
    }
    public function appName(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('app_name')
                    ->disabled(Auth::user()->cannot('Apply System Settings'))
                    ->placeholder('eg: Livecoat')
                    ->required()    
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $state) {
                        $this->authorize('Apply System Settings');
                        Setting::query()
                            ->updateOrCreate([
                                'key' => 'app_name'
                            ], [
                                'value' => ['name' => $state]
                            ]);
                        $this->doClearCache();
                        Notification::make()
                            ->title(__('App name updated successfully'))
                            ->success()
                            ->send();
                    })
                    ->hiddenLabel()
            ])
            ->statePath('dataAppName');
    }
    public function updateAppIcon()
    {
        $this->authorize('Apply System Settings');
        $data = $this->appIcon->getState();
        Setting::query()
                ->updateOrCreate([
                    'key' => 'app_icon'
                ], [
                    'value' => [
                        'path' => $data['app_icon']
                    ]
                ]);
        $this->doClearCache();
        Notification::make()
            ->title(__('App icon updated successfully'))
            ->success()
            ->send();
        $this->dispatch('close-modal', id: 'app-icon-modal');
    }
    public function updateLoginLogo()
    {
        $this->authorize('Apply System Settings');
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
        $this->doClearCache();
        Notification::make()
            ->title(__('Login logo updated successfully'))
            ->success()
            ->send();
        $this->dispatch('close-modal', id: 'login-logo-modal');
    }
    private function doClearCache()
    {
        Cache::forget('settings');
    }
    public function clearCache()
    {
        $this->doClearCache();
        Notification::make()
            ->title(__('Cache cleared successfully'))
            ->success()
            ->send();
        $this->redirect(route('app.settings.system-setting'));
    }
    public function updateAppLogo()
    {
        $this->authorize('Apply System Settings');
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
        $this->doClearCache();
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
                    ->live()
                    ->disabled(Auth::user()->cannot('Apply System Settings'))
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        $this->authorize('Apply System Settings');
                        $setting = Setting::query()->where('key', 'google_recaptcha_v2')->first();
                        if ($setting) {
                            $value = $setting->value;
                            $value['enabled'] = $state;
                            $setting->value = $value;
                            $setting->save();
                            } else {
                                Setting::query()
                                    ->updateOrCreate([
                                        'key' => 'google_recaptcha_v2'
                                    ], [
                                        'value' => [
                                            'enabled' => $state,
                                            'secret_key' => '',
                                            'site_key' => ''
                                        ]
                                    ]);
                            }
                        $this->doClearCache();
                        Notification::make()
                            ->title(__('Google Recaptcha v2 updated successfully'))
                            ->success()
                            ->send();
                    })
                // ...
            ])
            ->statePath('dataEnableGoogleRecaptcha');
    }
    public function setupGoogleRecaptcha(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('secret_key')
                        ->disabled(Auth::user()->cannot('Apply System Settings'))
                        ->placeholder('type secret key')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state) {
                            $this->authorize('Apply System Settings');
                            $setting = Setting::query()->where('key', 'google_recaptcha_v2')->first();
                            if ($setting) {
                                $value = $setting->value;
                                $value['secret_key'] = $state;
                                $setting->value = $value;
                                $setting->save();
                            } else {
                                Setting::query()
                                    ->updateOrCreate([
                                        'key' => 'google_recaptcha_v2'
                                    ], [
                                        'value' => [
                                            'enabled' => false,
                                            'secret_key' => $state,
                                            'site_key' => ''
                                        ]
                                    ]);
                            }
                            $this->doClearCache();
                            Notification::make()
                                ->title(__('Google Recaptcha secret key updated successfully'))
                                ->success()
                                ->send();
                        })
                        ->required(),
                TextInput::make('site_key')
                        ->disabled(Auth::user()->cannot('Apply System Settings'))
                        ->placeholder('type site key')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state) {
                            $this->authorize('Apply System Settings');
                            $setting = Setting::query()->where('key', 'google_recaptcha_v2')->first();
                            if ($setting) {
                                $value = $setting->value;
                                $value['site_key'] = $state;
                                $setting->value = $value;
                                $setting->save();
                            } else {
                                Setting::query()
                                    ->updateOrCreate([
                                        'key' => 'google_recaptcha_v2'
                                    ], [
                                        'value' => [
                                            'enabled' => false,
                                            'secret_key' => '',
                                            'site_key' => $state
                                        ]
                                    ]);
                            }
                            $this->doClearCache();
                            Notification::make()
                                ->title(__('Google Recaptcha site key updated successfully'))
                                ->success()
                                ->send();
                        })
                        ->required(),
            ])
            ->statePath('dataSetupGoogleRecaptcha');
    }
}
