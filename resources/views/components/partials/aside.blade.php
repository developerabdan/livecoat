<aside class="sidebar" data-side="left" aria-hidden="false">
    <nav aria-label="Sidebar navigation">
        <header>

            <a href="/" class="btn-ghost p-2 h-12 w-full justify-start">
                <div
                    class="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg">
                    <img src="{{ asset('logo-base.png') }}" alt="Logo">
                </div>
                <div class="grid flex-1 text-left text-sm leading-tight">
                    <span class="truncate font-medium">Livewire Basecoat</span>
                    <span class="truncate text-xs">v1.0.0</span>
                </div>
            </a>
        </header>
        <section class="scrollbar">
            <div role="group" aria-labelledby="group-label-content-1">
                <h3 id="group-label-content-1">{{ __('General') }}</h3>

                <ul>
                    <li>
                        <a wire:navigate href="{{ route('app.dashboard') }}">
                            <x-lucide-home />
                            <span>{{ __('Dashboard') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 8V4H8" />
                                <rect width="16" height="12" x="4" y="8" rx="2" />
                                <path d="M2 14h2" />
                                <path d="M20 14h2" />
                                <path d="M15 13v2" />
                                <path d="M9 13v2" />
                            </svg>
                            <span>{{ __('Models') }}</span>
                        </a>
                    </li>

                    <li>
                        <details id="submenu-content-1-3">
                            <summary aria-controls="submenu-content-1-3-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                {{ __('Settings') }}
                            </summary>
                            <ul id="submenu-content-1-3-content">
                                <li>
                                    <a wire:navigate href="{{ route('app.settings.permission') }}">
                                        <span>{{ __('Permissions') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </div>
        </section>
    </nav>
</aside>
