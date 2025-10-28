<x-layouts.main>
    <div class="p-4 md:p-6 xl:p-12">
        <header class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl xl:text-4xl">{{ __('Profiles') }}</h1>
            <p class="text-muted-foreground text-[1.05rem] sm:text-base">{{ __('Manage your profiles.') }}</p>
        </header>
        <div class="mt-6">
            <div class="card">
                <div class="p-4">
                    <div class="flex items-center space-x-6">
                        <!-- Profile Avatar -->
                        <div class="relative group w-28 h-28">
                            <img class="w-28 h-28 object-cover rounded-full border"
                                src="{{ asset('assets/images/profile-default.png') }}" alt="Profile" />
                            <!-- Hover pencil / upload -->
                            <label
                                class="absolute inset-0 flex items-center justify-center bg-black/50 text-white rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition">
                                <input type="file" class="hidden" />
                                ✏️
                            </label>
                        </div>

                        <!-- Name + Email -->
                        <div>
                            <h2 class="text-2xl font-semibold text-card-foreground">{{ Auth::user()->name }}</h2>
                            <p class="text-muted-foreground">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <hr class="my-8 border-muted" />

                    <!-- User Details -->
                    <div class="space-y-6">

                        <!-- Email -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground">Email</h3>
                                <p class="text-card-foreground">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <!-- Password (editable) -->
                        <div class="flex items-end">
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground">Password</h3>
                                <p class="text-card-foreground">••••••••••</p>
                            </div>
                            <button class="btn-link" @click="$dispatch('open-modal', { id: 'changePassword' })">
                                <x-lucide-pencil />
                            </button>
                        </div>

                        <!-- Role -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground mb-2">Registered Role</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(Auth::user()->roles as $role)
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-primary text-primary-foreground">{{ $role->name }}</span>
                                    @endforeach
                                </div>
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
</x-layouts.main>
