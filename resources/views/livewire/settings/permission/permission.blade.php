<x-layouts.main>
    <div class="p-4 md:p-6 xl:p-12">
        <header class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl xl:text-4xl">Permissions</h1>
            <p class="text-muted-foreground text-[1.05rem] sm:text-base">Manage your permissions.</p>
        </header>
        <div class="mt-6">
            <div class="card">
                <section class="accordion space-y-4" wire:ignore>
                    @foreach($this->permissionGroup as $item)
                    <details class="group border rounded-lg bg-primary-foreground shadow-sm">
                        <summary class="w-full cursor-pointer outline-none rounded-lg">
                            <div class="flex w-full justify-between items-center gap-4 p-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="shrink-0 w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
                                        <x-dynamic-component :component="$item->icon" class="w-6 h-6 text-primary-foreground" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h2 class="text-base font-semibold text-primary ">{{ $item->name }}</h2>
                                            <span class="text-sm font-medium text-gray-500">3/{{ $item->permissions->count() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-500">{{ $item->description }}</p>
                                    </div>
                                </div>
                                <x-lucide-chevron-up class="text-gray-400 shrink-0 transition-transform duration-200 group-open:rotate-180 w-5 h-5" />
                            </div>
                        </summary>
                        <div class="px-4 pb-4 space-y-2">
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-foreground-primary">
                                <div class="w-8 h-8 rounded-md bg-primary flex items-center justify-center">
                                    <x-lucide-lock class="text-primary-foreground w-4 h-4" />
                                </div>
                                <div class="flex w-full justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="text-sm font-medium text-primary">Create Users</h3>
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-primary text-primary-foreground">Granted</span>
                                        </div>
                                        <p class="text-xs text-gray-500">Add new users to the system</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="btn-sm-destructive text-xs">
                                            <x-lucide-lock />
                                        </button>
                                        <button class="btn-sm-secondary text-xs">
                                            <x-lucide-edit />
                                            Edit
                                        </button>
                                        <button class="btn-sm-secondary text-xs">
                                            <x-lucide-trash />
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @foreach($item->permissions as $permission)
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-foreground-primary">
                                <div class="w-8 h-8 rounded-md bg-primary/40 flex items-center justify-center">
                                    <x-lucide-lock class="text-primary-foreground w-4 h-4" />
                                </div>
                                <div class="flex w-full justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="text-sm font-medium text-primary">{{ $permission->name }}</h3>
                                            @if(auth()->user()->can($permission->name))
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-primary/40 text-white">Denied</span>
                                            @else
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-primary text-primary-foreground">Granted</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="btn-sm text-xs">
                                            <x-lucide-lock-open />
                                        </button>
                                        <button class="btn-sm-secondary text-xs">
                                            <x-lucide-edit />
                                            Edit
                                        </button>
                                        <button class="btn-sm-secondary text-xs">
                                            <x-lucide-trash />
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-foreground-primary">
                                <button @click="$dispatch('open-modal', { id: 'addPermission', data: { group_id: '01k8j9fz8zrvpx075hdyznn3fh' } })" class="btn-ghost w-full">{{ __('Add New Permissions') }} <x-lucide-shield-plus /></button>
                            </div>
                        </div>
                    </details>
                    @endforeach
                </section>
                <script>
                    (() => {
                        const accordions = document.querySelectorAll(".accordion");
                        accordions.forEach((accordion) => {
                            accordion.addEventListener("click", (event) => {
                                const summary = event.target.closest("summary");
                                if (!summary) return;
                                const details = summary.closest("details");
                                if (!details) return;
                                accordion.querySelectorAll("details").forEach((detailsEl) => {
                                    if (detailsEl !== details) {
                                        detailsEl.removeAttribute("open");
                                    }
                                });
                            });
                        });
                    })();
                </script>
            </div>
        </div>
    </div>
    <x-filament::modal id="addPermission">
        <x-slot name="heading">
            Add Permission
        </x-slot>
        <form wire:submit="create">
        {{  $this->form }}
        <button type="submit" class="btn mt-5">Create</button>
        </form>
    </x-filament::modal>
</x-layouts.main>
