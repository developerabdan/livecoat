<div class="flex min-h-svh w-full items-center justify-center p-6 md:p-10">
    <div class="w-full max-w-sm">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-48 w-auto">
        </div>
        {{ $slot }}
    </div>
</div>