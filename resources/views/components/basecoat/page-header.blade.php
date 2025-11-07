<header class="mb-5">
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl xl:text-4xl" >{{ $header ?? 'Page Heading' }}</h1>
            <p class="text-muted-foreground text-[1.05rem] sm:text-base">{{ $subHeader ?? '' }}</p>
        </div>
        <div class="flex items-center gap-2">
            {{ $action ?? '' }}
        </div>
    </div>
</header>