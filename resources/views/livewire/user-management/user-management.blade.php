<x-layouts.main>
    <div class="p-4 md:p-6 xl:p-12">
        <header class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl xl:text-4xl">{{ __('User Management') }}</h1>
            <p class="text-muted-foreground text-[1.05rem] sm:text-base">{{ __('Manage users, roles, and permissions.') }}</p>
        </header>
        <div class="mt-6">
            {{ $this->table }}
        </div>
    </div>
    
    <x-filament-actions::modals />
</x-layouts.main>