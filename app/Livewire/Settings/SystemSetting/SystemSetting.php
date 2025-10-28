<?php

namespace App\Livewire\Settings\SystemSetting;

use App\Models\Setting;
use Livewire\Component;
use Filament\Schemas\Schema;
use Livewire\Attributes\Title;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;

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
        $this->enableTotp->fill();
        $this->appIcon->fill();
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
                    ->image()
                    ->hiddenLabel()
                    ->imageEditor()
                    ->directory('systems')
                    ->disk('public')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('100')
                    ->imageResizeTargetHeight('100')
                    ->required(),
                // ...
            ])
            ->statePath('data');
    }
    public function appLogo(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('app_logo')
                    ->image()
                    ->hiddenLabel()
                    ->imageEditor()
                    ->directory('systems')
                    ->disk('public')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('100')
                    ->imageResizeTargetHeight('100')
                    ->required(),
                // ...
            ])
            ->statePath('data');
    }
    public function updateAppIcon()
    {
        $data = $this->appIcon->getState();
        Setting::query()
            ->where('key', 'app_icon')
            ->update([
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
            ->where('key', 'app_logo')
            ->update([
                'value' => [
                    'path' => $data['app_logo']
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
