@props(['isHome', 'isShow', 'route', 'color' => 'gray', 'majorCategories'])

@php
$classes = ($isHome || $isShow)
    ? "block text-sm px-4 py-2 rounded bg-{$color}-200 text-{$color}-900"
    : "block text-sm px-4 py-2 rounded text-{$color}-700 hover:bg-{$color}-100";
$arrayUrl = explode('/', url()->current());
$currentCategory = urldecode(end($arrayUrl));
@endphp
<div {{ $attributes->merge(['class' => $classes]) }}>
    <a href="{{ route($route) }}" class="block {{ $isHome || $isShow ? 'pointer-events-none' : ''}}">
        {{ $slot }}
    </a>
    @if ($isHome || $isShow)
    <ul>
        <li>
            <a href="{{ route('home') }}" class="block {{ $isHome ? 'text-white bg-gray-400 pointer-events-none' : '' }}">全て</a>
        </li>
        @foreach ($majorCategories as $majorCategory)
        <li>
            <a href="{{ route('show', $majorCategory->name) }}" class="block {{ $majorCategory->name == $currentCategory ? 'text-white bg-gray-400 pointer-events-none' : '' }}">{{ $majorCategory->name }}</a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
