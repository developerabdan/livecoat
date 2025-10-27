<div>
    @php
        $menu = [
            [
                'group' => __('General'),
                'items' => [
                    [
                        'title' => __('Dashboard'),
                        'icon' => 'lucide-home',
                        'route' => route('app.dashboard'),
                        'active' => request()->routeIs('app.dashboard'),
                    ],
                    [
                        'title' => __('Settings'),
                        'icon' => 'lucide-settings',
                        'active' => request()->routeIs('app.settings.*'),
                        'sub' => [
                            [
                                'title' => __('Permissions'),
                                'route' => route('app.settings.permission'),
                                'active' => request()->routeIs('app.settings.permission'),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    @endphp
    <x-partials.aside :menu="$menu" />
    <main>
        <x-partials.header />
        {{ $slot }}
    </main>
</div>