@props([
    'header' => null,
    'subHeader' => null,
])
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
                        'permission' => null
                    ],
                    [
                        'title' => __('User Access'),
                        'icon' => 'lucide-user',
                        'route' => route('app.user'),
                        'active' => request()->routeIs('app.user'),
                        'permission' => 'View Users'
                    ],
                    [
                        'title' => __('Settings'),
                        'icon' => 'lucide-settings',
                        'active' => request()->routeIs('app.settings.*'),
                        'sub' => [
                            [
                                'title' => __('Permission Groups'),
                                'route' => route('app.settings.permission-group'),
                                'active' => request()->routeIs('app.settings.permission-group'),
                                'permission' => 'View Permission Groups'
                            ],
                            [
                                'title' => __('Permissions'),
                                'route' => route('app.settings.permission'),
                                'active' => request()->routeIs('app.settings.permission'),
                                'permission' => 'View Permissions'
                            ],
                            [
                                'title' => __('Roles'),
                                'route' => route('app.settings.role'),
                                'active' => request()->routeIs('app.settings.role'),
                                'permission' => 'View Roles'
                            ],
                            [
                                'title' => __('System Settings'),
                                'route' => route('app.settings.system-setting'),
                                'active' => request()->routeIs('app.settings.system-setting'),
                                'permission' => 'View System Settings'
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
        <div class="p-4 md:p-6 xl:p-12">
            {{ $slot }}
        </div>
    </main>
</div>