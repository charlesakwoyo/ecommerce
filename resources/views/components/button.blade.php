@props(['variant' => 'primary'])

@php
    $variants = [
        'primary' => 'bg-brand-500 text-white hover:bg-brand-600 disabled:bg-brand-300',
        'secondary' => 'border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800',
        'danger' => 'bg-red-600 text-white hover:bg-red-500 disabled:bg-red-300',
    ];
@endphp

<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center justify-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition disabled:cursor-not-allowed '.$variants[$variant],
    ]) }}
>
    {{ $slot }}
</button>
