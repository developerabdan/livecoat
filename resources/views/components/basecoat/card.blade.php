@props([
    'header' => null,
    'subHeader' => null,
    'footer' => null,
])
<div class="card">
    @if($header)
    <header>
        <h2>{{ $header }}</h2>
        {{ $subHeader }}
    </header>
    @endif
    <section>
        {{ $slot }}
    </section>
    @if($footer)
    <footer class="flex items-center">
        {{ $footer }}
    </footer>
    @endif
</div>
