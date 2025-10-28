@props(['menu' => []])

<aside class="sidebar" data-side="left" aria-hidden="false">
    <nav aria-label="Sidebar navigation" style="z-index:35 !important;">
        <header>

            <a href="/" class="btn-ghost p-2 h-12 w-full justify-start">
                <div class=" flex aspect-square size-8 items-center justify-center rounded-lg">
                    <img src="{{ setting('app_logo.path') ? asset('storage/' . setting('app_logo.path')) : asset('logo-base.png') }}"
                        alt="Logo">
                </div>
                <div class="grid flex-1 text-left text-sm leading-tight">
                    <span class="truncate font-medium">{{ setting('app_name.name') ?? config('app.name') }}</span>
                    <span class="truncate text-xs">v{{ setting('app_version.version') ?? config('app.version') }}</span>
                </div>
            </a>
        </header>
        <section class="scrollbar" wire:ignore>
            @foreach ($menu as $groupIndex => $group)
                <div role="group" aria-labelledby="group-label-content-{{ $groupIndex }}">
                    <h3 id="group-label-content-{{ $groupIndex }}">{{ $group['group'] }}</h3>

                    <ul>
                        @foreach ($group['items'] as $itemIndex => $item)
                            @if (!isset($item['permission']) || auth()->user()->can($item['permission']))
                                <li>
                                    @if (isset($item['sub']) && count($item['sub']) > 0)
                                        {{-- Menu item with submenu --}}
                                        @php
                                            $visibleSubItems = collect($item['sub'])->filter(function ($subItem) {
                                                return !isset($subItem['permission']) ||
                                                    auth()->user()->can($subItem['permission']);
                                            });
                                        @endphp
                                        @if ($visibleSubItems->isNotEmpty())
                                            <details id="submenu-content-{{ $groupIndex }}-{{ $itemIndex }}"
                                                {{ $item['active'] ?? false ? 'open' : '' }}>
                                                <summary
                                                    aria-controls="submenu-content-{{ $groupIndex }}-{{ $itemIndex }}-content">
                                                    <x-dynamic-component :component="$item['icon']" />
                                                    {{ $item['title'] }}
                                                </summary>
                                                <ul
                                                    id="submenu-content-{{ $groupIndex }}-{{ $itemIndex }}-content">
                                                    @foreach ($item['sub'] as $subItem)
                                                        @if (!isset($subItem['permission']) || auth()->user()->can($subItem['permission']))
                                                            <li>
                                                                <a wire:navigate href="{{ $subItem['route'] }}"
                                                                    @class(['bg-gray-50/5 font-medium' => $subItem['active'] ?? false])>
                                                                    <span>{{ $subItem['title'] }}</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </details>
                                        @endif
                                    @else
                                        {{-- Regular menu item --}}
                                        <a @class(['bg-gray-50/5 font-medium' => $item['active'] ?? false]) wire:navigate href="{{ $item['route'] }}">
                                            <x-dynamic-component :component="$item['icon']" />
                                            <span>{{ $item['title'] }}</span>
                                        </a>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </section>
        <footer>
            <div id="popover-account" class="popover">
                <button id="popover-account-trigger" type="button" aria-expanded="false"
                    aria-controls="popover-account-popover"
                    class="btn-ghost p-2 h-12 w-full flex items-center justify-start" data-keep-mobile-sidebar-open="">

                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/images/profile-default.png') }}"
                        class="rounded-lg shrink-0 size-8">
                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span class="truncate font-medium">{{ auth()->user()->name }}</span>
                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m7 15 5 5 5-5"></path>
                        <path d="m7 9 5-5 5 5"></path>
                    </svg>

                </button>
                <div id="popover-account-popover" data-popover="" aria-hidden="true" data-side="top"
                    class="w-[271px] md:w-[239px]">

                    <div class="grid gap-3">
                        <!-- User Info Header -->
                        <div class="flex items-center gap-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/images/profile-default.png') }}"
                                class="rounded-full shrink-0 size-10 ring-2 ring-gray-200 dark:ring-gray-700">
                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span
                                    class="truncate font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</span>
                                <span
                                    class="truncate text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="grid gap-2">
                            <a wire:navigate href="{{ route('app.profile') }}"
                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-150"
                                wire:navigate>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Profile</span>
                            </a>

                            <a wire:navigate href="{{ route('app.logout') }}"
                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
                                wire:navigate>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" x2="9" y1="12" y2="12"></line>
                                </svg>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </footer>
    </nav>
</aside>
