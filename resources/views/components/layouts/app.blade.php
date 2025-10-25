<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Blank' }} | {{ config('app.name') }}</title>
    @vite('resources/css/app.css')
    <script src="{{ asset('assets/js/basecoat.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/sidebar.min.js') }}" defer></script>
</head>

<body>
    {{ $slot }}
</body>

</html>
