@props(['type' => 'info', 'title' => null, 'message' => null])

@php
    $types = [
        'success' => [
            'class' => 'alert-success',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="m9 12 2 2 4-4" /></svg>',
        ],
        'error' => [
            'class' => 'alert-destructive',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><line x1="12" x2="12" y1="8" y2="12" /><line x1="12" x2="12.01" y1="16" y2="16" /></svg>',
        ],
        'warning' => [
            'class' => 'alert-warning',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" /><line x1="12" x2="12" y1="9" y2="13" /><line x1="12" x2="12.01" y1="17" y2="17" /></svg>',
        ],
        'info' => [
            'class' => 'alert-info',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="M12 16v-4" /><path d="M12 8h.01" /></svg>',
        ],
    ];

    $alertConfig = $types[$type] ?? $types['info'];
@endphp

<div class="alert {{ $alertConfig['class'] }} mb-3" {{ $attributes }}>
    {!! $alertConfig['icon'] !!}
    @if($title)
        <h2>{{ $title }}</h2>
    @endif
    @if($message)
        <section>{{ $message }}</section>
    @endif
    @if($slot->isNotEmpty())
        <section>{{ $slot }}</section>
    @endif
</div>
