@props(['active', 'route', 'color' => 'gray'])

@php
$classes = ($active ?? false)
    ? "block text-sm px-4 py-2 rounded bg-{$color}-200 text-{$color}-900 pointer-events-none"
    : "block text-sm px-4 py-2 rounded text-{$color}-700 hover:bg-{$color}-100";
@endphp

<a href="{{ route($route) }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
