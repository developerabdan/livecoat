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
                'value' => []
            ],
            [
                'key' => 'app_icon',
                'value' => []
            ],
            [
                'key' => 'app_logo',
                'value' => []
            ],
            [
                'key' => 'login_logo',
                'value' => []
            ],
            [
                'key' => 'app_version',
                'value' => []
            ],
            [
                'key' => 'google_recaptcha_v2',
                'value' => [
                    'enabled' => false,
                    'secret_key' => '',
                    'site_key' => ''
                ]
            ],
            [
                'key' => 'enable_totp',
                'value' => []
            ],
        ];
        foreach ($settings as $setting) {
            Setting::query()
                ->createOrFirst($setting);
        }
    }
}
