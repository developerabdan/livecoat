@props(['menu' => []])

<aside class="sidebar" data-side="left" aria-hidden="false">
    <nav aria-label="Sidebar navigation" style="z-index:35 !important;">
        <header>

            <a href="/" class="btn-ghost p-2 h-12 w-full justify-start">
                <div
                    class="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg">
                    <img src="{{ asset('logo-base.png') }}" alt="Logo">
                </div>
                <div class="grid flex-1 text-left text-sm leading-tight">
                    <span class="truncate font-medium">{{ config('app.name') }}</span>
                    <span class="truncate text-xs">v1.0.0</span>
                </div>
            </a>
        </header>
        <section class="scrollbar">
            @foreach ($menu as $groupIndex => $group)
                <div role="group" aria-labelledby="group-label-content-{{ $groupIndex }}">
                    <h3 id="group-label-content-{{ $groupIndex }}">{{ $group['group'] }}</h3>

                    <ul>
                        @foreach ($group['items'] as $itemIndex => $item)
                            <li>
                                @if (isset($item['sub']) && count($item['sub']) > 0)
                                    {{-- Menu item with submenu --}}
                                    <details id="submenu-content-{{ $groupIndex }}-{{ $itemIndex }}" {{ $item['active'] ?? false ? 'open' : '' }}>
                                        <summary aria-controls="submenu-content-{{ $groupIndex }}-{{ $itemIndex }}-content">
                                            <x-dynamic-component :component="$item['icon']" />
                                            {{ $item['title'] }}
                                        </summary>
                                        <ul id="submenu-content-{{ $groupIndex }}-{{ $itemIndex }}-content">
                                            @foreach ($item['sub'] as $subItem)
                                                <li>
                                                    <a wire:navigate href="{{ $subItem['route'] }}" @class(['bg-gray-50/5 font-medium' => $subItem['active'] ?? false])>
                                                        <span>{{ $subItem['title'] }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </details>
                                @else
                                    {{-- Regular menu item --}}
                                    <a @class(['bg-gray-50/5 font-medium' => $item['active'] ?? false]) wire:navigate href="{{ $item['route'] }}">
                                        <x-dynamic-component :component="$item['icon']" />
                                        <span>{{ $item['title'] }}</span>
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </section>
    </nav>
</aside>
