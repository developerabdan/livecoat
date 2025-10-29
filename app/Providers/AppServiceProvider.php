<?php

namespace App\Providers;

use App\Models\Setting;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin');
        });
        // Check if settings migration has been run
        if (Schema::hasTable('settings')) {
            $settings = Cache::rememberForever('settings', function () {
                return Setting::pluck('value', 'key')->toArray();
            });
            config(['app.settings' => $settings]);
        } else {
            $settings = [];
            config(['app.settings' => $settings]);
        }
    }
}
