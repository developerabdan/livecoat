<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Blank' }} | {{ config('app.name') }}</title>
    @vite('resources/css/app.css')

</head>

<body>

    <div class="flex min-h-svh w-full items-center justify-center p-6 md:p-10">
        <div class="w-full max-w-sm">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
