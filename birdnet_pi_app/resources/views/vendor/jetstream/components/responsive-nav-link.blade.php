@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block pl-3 pr-4 py-2 border-l-4 border-green-400 text-base font-semibold text-green-800 bg-green-400 focus:outline-none focus:text-slate-900 focus:bg-green-400 focus:border-green-700 transition'
            : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-gray-400 dark:hover:text-slate-900 hover:bg-green-400 hover:border-gray-300 focus:outline-none focus:text-slate-900 focus:bg-green-400 focus:border-green-300 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
