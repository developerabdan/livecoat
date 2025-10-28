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
</x-layouts.main>
