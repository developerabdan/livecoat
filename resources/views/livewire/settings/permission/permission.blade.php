<x-layouts.main>
    <div class="p-4 md:p-6 xl:p-12">
        <header class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl xl:text-4xl">Permissions</h1>
            <p class="text-muted-foreground text-[1.05rem] sm:text-base">Manage your permissions.</p>
        </header>
        <div class="mt-6">
            <div class="card">
                <section class="accordion space-y-4">
                    @foreach($this->permissionGroup as $category => $permissionGroup)
                    <h3 class="font-semibold text-xl sm:text-xl xl:text-xl flex items-center gap-2"><x-lucide-grip class="w-5 h-5" /> {{ Str::title(Str::replace('-',' ',$category)) }}</h3>
                    @foreach($permissionGroup as $item)
                    <details class="group border rounded-lg bg-primary-foreground shadow-sm" wire:ignore.self>
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
                            @foreach($item->permissions as $permission)
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-foreground-primary">
                                <div @class([
                                    "w-8 h-8 rounded-md flex items-center justify-center",
                                    "bg-primary/40" => auth()->user()->cannot($permission->name),
                                    "bg-primary" => auth()->user()->can($permission->name)
                                ])
                                >
                                    <x-lucide-lock class="text-primary-foreground w-4 h-4" />
                                </div>
                                <div class="flex w-full justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="text-sm font-medium text-primary">{{ $permission->name }}</h3>
                                            @if(auth()->user()->cannot($permission->name))
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-primary/40 text-white">Denied</span>
                                            @else
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-primary text-primary-foreground">Granted</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        @can('Edit Permissions')
                                        <button class="btn-sm-secondary text-xs" @click="$dispatch('open-modal', { id: 'editPermission', data: { id: '{{ $permission->id }}' } })">
                                            <x-lucide-edit />
                                            Edit
                                        </button>
                                        @endcan
                                        @can('Delete Permissions')
                                        <button class="btn-sm-secondary text-xs" @click="$dispatch('open-modal', { id: 'confirmDelete', data: { id: '{{ $permission->id }}' } })">
                                            <x-lucide-trash />
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @can('Create Permissions')
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-foreground-primary">
                                <button @click="$dispatch('open-modal', { id: 'addPermission', data: { group_id: '{{ $item->id  }}' } })" class="btn-ghost w-full">{{ __('Add New Permissions') }} <x-lucide-shield-plus /></button>
                            </div>
                            @endcan
                        </div>
                    </details>
                    @endforeach
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
    <x-filament::modal id="editPermission">
        <x-slot name="heading">
            Edit Permission
        </x-slot>
        <form wire:submit="update">
        {{  $this->form }}
        <button type="submit" class="btn mt-5">Update</button>
        </form>
    </x-filament::modal>
    <x-filament::modal id="confirmDelete">
        <x-slot name="heading">
            Delete Permission
        </x-slot>
        <form wire:submit="delete">
            <p>Are you sure you want to delete this permission?</p>
        <button type="submit" class="btn mt-5">Delete</button>
        </form>
    </x-filament::modal>
</x-layouts.main>
