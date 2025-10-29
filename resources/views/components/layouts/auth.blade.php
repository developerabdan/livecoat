<div class="flex min-h-svh w-full items-center justify-center p-6 md:p-10">
    <div class="w-full max-w-sm">
        <div class="flex justify-center mb-6">
            <img src="{{ setting('login_logo.path_light') ? asset('storage/' . setting('login_logo.path_light')) : asset('logo.png') }}" alt="Logo" class="h-48 w-auto dark:hidden">
            <img src="{{ setting('login_logo.path_dark') ? asset('storage/' . setting('login_logo.path_dark')) : asset('logo.png') }}" alt="Logo" class="h-48 w-auto hidden dark:block">
        </div>
        {{ $slot }}
    </div>
</div>