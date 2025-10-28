<?php

namespace Database\Seeders;

use App\Livewire\Settings\SystemSetting\SystemSetting;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key'  => 'app_name',
                'value' => json_encode([
                    'name' => 'Livecoat',
                ]),
            ],
            [
                'key' => 'app_icon',
                'value' => json_encode([
                    'path' => '/public/assets/img/favicon.png',
                ])
            ],
            [
                'key' => 'app_logo',
                'value' => json_encode([
                    'path' => '/public/logo-square.png',
                ])
            ],
            [
                'key' => 'login_logo',
                'value' => json_encode([
                    'path' => '/public/logo.png',
                ])
            ],
            [
                'key' => 'app_version',
                'value' => json_encode([
                    'version' => '1.0.0',
                ])
            ],
            [
                'key' => 'google_recaptcha_v2',
                'value' => json_encode([
                    'site_key' => '',
                    'secret_key' => '',
                ])
            ],
        ];
        foreach ($settings as $setting) {
            Setting::query()
                ->createOrFirst($setting);
        }
    }
}
