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
                                'title' => __('Permission Groups'),
                                'route' => route('app.settings.permission-group'),
                                'active' => request()->routeIs('app.settings.permission-group')
                            ],
                            [
                                'title' => __('Permissions'),
                                'route' => route('app.settings.permission'),
                                'active' => request()->routeIs('app.settings.permission'),
                            ],
                            [
                                'title' => __('Roles'),
                                'route' => route('app.settings.role'),
                                'active' => request()->routeIs('app.settings.role'),
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