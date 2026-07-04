@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 my-auto bg-indigo-100/80 dark:bg-indigo-500/20 rounded-full text-sm font-bold leading-5 text-indigo-700 dark:text-indigo-400 shadow-sm transition duration-300 ease-in-out transform scale-105'
            : 'inline-flex items-center px-4 py-2 my-auto rounded-full text-sm font-semibold leading-5 text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-300 hover:bg-indigo-50/80 dark:hover:bg-slate-800 transition duration-300 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
