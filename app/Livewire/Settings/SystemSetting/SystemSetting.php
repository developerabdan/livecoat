<?php

namespace App\Livewire\Settings\SystemSetting;

use Filament\Forms\Components\Toggle;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('System Settings')]
class SystemSetting extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    public function render()
    {
        return view('livewire.settings.system-setting.system-setting');
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
