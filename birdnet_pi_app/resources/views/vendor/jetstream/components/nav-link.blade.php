@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-green-400 text-sm font-medium leading-5 text-green-500 dark:text-green-300 dark:font-semibold focus:outline-none focus:border-green-700 transition'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 hover:border-green-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
