<x-layouts.main>
    <div class="p-4 md:p-6 xl:p-12">
        <header class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl xl:text-4xl">{{ __('System Settings') }}</h1>
            <p class="text-muted-foreground text-[1.05rem] sm:text-base">{{ __('Manage system settings') }}</p>
        </header>
        <div class="mt-6 flex flex-col gap-2">
            <div class="card">
                <div class="p-4">
                    <section class="text-sm grid gap-6">
                        <label class="flex items-center justify-between gap-2">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">{{ __('Clear Cache') }}</div>
                                <div class="text-muted-foreground">
                                    {{ __('Clear the application cache to ensure that your changes take effect.') }}
                                </div>
                            </div>
                            <button class="btn-destructive"
                                @click="$dispatch('open-modal', { id: 'clear-cache-modal'})">{{ __('Clear') }}</button>
                        </label>
                    </section>
                </div>
            </div>
            <div class="card">
                <div class="p-4">
                    <section class="text-sm grid gap-6">
                        <label class="flex items-center justify-between gap-2">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">{{ __('App Version') }}</div>
                                <div class="text-muted-foreground">
                                    {{ __('Configure your app version.') }}</div>
                            </div>
                            {{ $this->appVersion }}
                        </label>
                        <label class="flex items-center justify-between gap-2">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">{{ __('App Name') }}</div>
                                <div class="text-muted-foreground">
                                    {{ __('Configure your app name.') }}</div>
                            </div>
                            {{ $this->appName }}
                        </label>
                        <label class="flex items-center justify-between gap-2">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">{{ __('App Icon') }}</div>
                                <div class="text-muted-foreground">
                                    {{ __('Upload the image that will be used as your app’s icon.”') }}</div>
                            </div>
                            <button class="btn-secondary"
                                @click="$dispatch('open-modal', { id: 'app-icon-modal'})">{{ __('Manage') }}</button>
                        </label>
                        <label class="flex items-center justify-between gap-2">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">{{ __('App Logo') }}</div>
                                <div class="text-muted-foreground">
                                    {{ __('Configure the image asset used as your app’s primary logo.') }}</div>
                            </div>
                            <button class="btn-secondary"
                                @click="$dispatch('open-modal', { id: 'app-logo-modal'})">{{ __('Manage') }}</button>
                        </label>
                        <label class="flex items-center justify-between gap-2">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">{{ __('Login Logo') }}</div>
                                <div class="text-muted-foreground">
                                    {{ __('Configure the image asset used as your login page logo.') }}</div>
                            </div>
                            <button class="btn-secondary"
                                @click="$dispatch('open-modal', { id: 'login-logo-modal'})">{{ __('Manage') }}</button>
                        </label>
                    </section>
                </div>
            </div>
            <div class="card">
                <div class="p-4">
                    <section class="text-sm grid gap-6">
                        <label class="flex items-center justify-between gap-2">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">
                                    {{ __('Enable Time-Based One-Time Passwords (TOTP) Authenticator') }}</div>
                                <div class="text-muted-foreground">
                                    {{ __('Add an extra layer of security to your account by generating time-based one-time passcodes using a compatible authenticator app.') }}
                                </div>
                            </div>
                            {{ $this->enableTotp }}
                        </label>
                    </section>
                </div>
            </div>
            <div class="card">
                <div class="flex flex-col gap-4 p-4">
                    <section class="text-sm grid gap-6">
                        <label class="flex items-center justify-between gap-2">
                            <div class="flex flex-col gap-0.5">
                                <div class="font-medium">{{ __('Enable Google Recaptcha v2') }}</div>
                                <div class="text-muted-foreground">
                                    {{ __('Enable Google reCAPTCHA to verify human interaction and prevent automated submissions.') }}
                                </div>
                            </div>
                            {{ $this->enableGoogleRecaptcha }}
                        </label>
                    </section>
                    <form action="" class="flex flex-col gap-4 form">
                        {{ $this->setupGoogleRecaptcha }}
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-filament::modal id="app-icon-modal">
        <x-slot name="heading">
            {{ __('Manage App Icon') }}
        </x-slot>
        <form wire:submit.prevent="updateAppIcon">
            {{ $this->appIcon }}
            <button type="submit" class="btn mt-5">{{ __('Upload') }}</button>
        </form>
    </x-filament::modal>
    <x-filament::modal id="app-logo-modal">
        <x-slot name="heading">
            {{ __('Manage App Logo') }}
        </x-slot>
        <form wire:submit.prevent="updateAppLogo">
            {{ $this->appLogo }}
            <button type="submit" class="btn mt-5">{{ __('Upload') }}</button>
        </form>
    </x-filament::modal>
    <x-filament::modal id="login-logo-modal">
        <x-slot name="heading">
            {{ __('Manage Login Logo') }}
        </x-slot>
        <form wire:submit.prevent="updateLoginLogo">
            {{ $this->loginLogo }}
            <button type="submit" class="btn mt-5">{{ __('Upload') }}</button>
        </form>
    </x-filament::modal>
    <x-filament::modal id="clear-cache-modal">
        <x-slot name="heading">
            {{ __('Are you sure you want to clear the cache?') }}
        </x-slot>
        <form wire:submit.prevent="clearCache">
            <button type="submit" class="btn w-full">{{ __('Clear') }}</button>
        </form>
    </x-filament::modal>
</x-layouts.main>
