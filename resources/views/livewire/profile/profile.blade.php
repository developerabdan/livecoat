<x-layouts.main>
    <div class="p-4 md:p-6 xl:p-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight">{{ __('Profile') }}</h1>
            <p class="text-muted-foreground text-sm mt-1">{{ __('Manage your account settings and preferences') }}</p>
        </div>

        <!-- Profile Grid Layout -->
        <div class="grid gap-4 md:grid-cols-3">
            <!-- Profile Card - Left Side -->
            <div class="card md:col-span-1">
                <div class="p-5">
                    <div class="flex flex-col items-center text-center">
                        <!-- Profile Avatar -->
                        <div class="relative group w-64 h-64 mb-4">
                            <img class="w-64 h-64 object-cover rounded-full border-2 border-muted"
                                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/profile-default.png') }}" 
                                alt="Profile" />
                            <!-- Hover pencil / upload -->
                            <button 
                                class="absolute inset-0 flex items-center justify-center bg-black/60 text-white rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity duration-200"
                                @click="$dispatch('open-modal', { id: 'changeAvatar' })">
                                <x-lucide-pencil class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Name + Email -->
                        <h2 class="text-xl font-semibold text-card-foreground">{{ Auth::user()->name }}</h2>
                        <p class="text-muted-foreground text-sm mt-1">{{ Auth::user()->email }}</p>

                        <!-- Roles -->
                        <div class="mt-4 w-full">
                            <div class="flex flex-wrap gap-1.5 justify-center">
                                @foreach(Auth::user()->roles as $role)
                                    <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information - Right Side -->
            <div class="card md:col-span-2">
                <div class="p-5">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Account Information') }}</h3>
                    
                    <div class="space-y-4">
                        <!-- Email -->
                        <div class="flex items-center justify-between py-3 border-b border-muted">
                            <div class="flex-1">
                                <label class="text-xs font-medium text-muted-foreground uppercase tracking-wide">{{ __('Email Address') }}</label>
                                <p class="text-sm text-card-foreground mt-1">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="flex items-center justify-between py-3 border-b border-muted">
                            <div class="flex-1">
                                <label class="text-xs font-medium text-muted-foreground uppercase tracking-wide">{{ __('Password') }}</label>
                                <p class="text-sm text-card-foreground mt-1">••••••••••</p>
                            </div>
                            <button 
                                class="btn-ghost btn-sm gap-2" 
                                @click="$dispatch('open-modal', { id: 'changePassword' })">
                                <x-lucide-pencil class="w-4 h-4" />
                                <span>{{ __('Change') }}</span>
                            </button>
                        </div>

                        <!-- Account Created -->
                        <div class="flex items-center justify-between py-3">
                            <div class="flex-1">
                                <label class="text-xs font-medium text-muted-foreground uppercase tracking-wide">{{ __('Member Since') }}</label>
                                <p class="text-sm text-card-foreground mt-1">{{ Auth::user()->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-3">
                            <div class="flex-1">
                                <label class="text-xs font-medium text-muted-foreground uppercase tracking-wide">{{ __('Two-Factor Authentication') }}</label>
                                <p class="text-sm text-card-foreground mt-1">
                                    @if(Auth::user()->totp_secret)
                                        <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400">
                                            <x-lucide-shield-check class="w-4 h-4" />
                                            {{ __('Enabled') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-muted-foreground">
                                            <x-lucide-shield-off class="w-4 h-4" />
                                            {{ __('Disabled') }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <button 
                                class="btn-ghost btn-sm gap-2" 
                                @click="$dispatch('open-modal', { id: 'setup2fa' })">
                                @if(Auth::user()->totp_secret)
                                    <x-lucide-settings class="w-4 h-4" />
                                    <span>{{ __('Manage') }}</span>
                                @else
                                    <x-lucide-shield-plus class="w-4 h-4" />
                                    <span>{{ __('Enable') }}</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-filament::modal id="changePassword">
        <x-slot name="heading">
            Change Password
        </x-slot>
        <form wire:submit="changePassword">
            {{ $this->changePasswordSchema }}
            <button type="submit" class="btn mt-5">Change Password</button>
        </form>
    </x-filament::modal>
    <x-filament::modal id="changeAvatar">
        <x-slot name="heading">
            Change Avatar
        </x-slot>
        <form wire:submit="changeAvatar">
            {{ $this->changeAvatarSchema }}
            <button type="submit" class="btn mt-5">Change Avatar</button>
        </form>
    </x-filament::modal>

    <!-- 2FA Setup Modal -->
    <x-filament::modal id="setup2fa" width="2xl">
        <x-slot name="heading">
            {{ Auth::user()->totp_secret ? __('Manage Two-Factor Authentication') : __('Enable Two-Factor Authentication') }}
        </x-slot>
        
        <div class="space-y-4">
            @if(Auth::user()->totp_secret)
                <!-- 2FA is already enabled -->
                <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center gap-2 text-green-800 dark:text-green-200">
                        <x-lucide-shield-check class="w-5 h-5" />
                        <span class="font-medium">{{ __('Two-factor authentication is enabled') }}</span>
                    </div>
                    <p class="text-sm text-green-700 dark:text-green-300 mt-2">
                        {{ __('Your account is protected with two-factor authentication.') }}
                    </p>
                </div>
                
                <div class="flex gap-2 justify-end">
                    <button type="button" wire:click="disable2fa" class="btn-destructive">
                        {{ __('Disable 2FA') }}
                    </button>
                </div>
            @else
                <!-- 2FA is not enabled yet -->
                @if(!$qrCode)
                    <!-- Show enable button -->
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-start gap-2 text-blue-800 dark:text-blue-200">
                                <x-lucide-info class="w-5 h-5 mt-0.5" />
                                <div>
                                    <p class="font-medium">{{ __('Enhance your account security') }}</p>
                                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                        {{ __('Two-factor authentication adds an extra layer of security by requiring a code from your authenticator app in addition to your password.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" wire:click="enable2fa" class="btn w-full">
                            <x-lucide-shield-plus class="w-4 h-4" />
                            {{ __('Enable 2FA') }}
                        </button>
                    </div>
                @else
                    <!-- Show QR code and verification -->
                    <div class="space-y-4">
                        <div class="text-center">
                            <h3 class="font-semibold mb-2">{{ __('Scan QR Code') }}</h3>
                            <p class="text-sm text-muted-foreground mb-4">
                                {{ __('Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.)') }}
                            </p>
                            <div class="flex justify-center mb-4">
                                <img src="{{ $qrCode }}" alt="QR Code" class="w-64 h-64 border border-muted rounded-lg" />
                            </div>
                        </div>
                        
                        <div>
                            <label for="verificationCode" class="block text-sm font-medium mb-2">
                                {{ __('Verification Code') }}
                            </label>
                            <input 
                                type="text" 
                                id="verificationCode"
                                wire:model="verificationCode"
                                placeholder="{{ __('Enter 6-digit code') }}"
                                class="input w-full text-center text-lg tracking-widest"
                                maxlength="6"
                                pattern="[0-9]{6}"
                            />
                            <p class="text-xs text-muted-foreground mt-1">
                                {{ __('Enter the 6-digit code from your authenticator app') }}
                            </p>
                        </div>
                        
                        <div class="flex gap-2 justify-end">
                            <button type="button" wire:click="cancel2faSetup" class="btn-ghost">
                                {{ __('Cancel') }}
                            </button>
                            <button type="button" wire:click="verify2fa" class="btn">
                                {{ __('Verify & Enable') }}
                            </button>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </x-filament::modal>
</x-layouts.main>
