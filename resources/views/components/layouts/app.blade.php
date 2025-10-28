<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        (() => {
            try {
                const stored = localStorage.getItem('themeMode');
                if (stored ? stored === 'dark' :
                    matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
            } catch (_) {}

            const apply = dark => {
                document.documentElement.classList.toggle('dark', dark);
                try {
                    localStorage.setItem('themeMode', dark ? 'dark' : 'light');
                } catch (_) {}
            };

            document.addEventListener('basecoat:theme', (event) => {
                const mode = event.detail?.mode;
                apply(mode === 'dark' ? true :
                    mode === 'light' ? false :
                    !document.documentElement.classList.contains('dark'));
            });

            document.addEventListener('livewire:navigated', () => {
                const stored = localStorage.getItem('themeMode');
                const darkMode = stored === 'dark' || (stored === null && matchMedia('(prefers-color-scheme: dark)').matches);
                apply(darkMode);
            });
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Blank' }} | {{ config('app.name') }}</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @filamentStyles
    @vite('resources/css/app.css')
    <script src="{{ asset('assets/js/basecoat.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/sidebar.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/popover.min.js') }}?id={{ rand() }}" data-navigate-track defer></script>
</head>

<body class="antialiased">
    {{ $slot }}
    @livewire('notifications')
    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>
