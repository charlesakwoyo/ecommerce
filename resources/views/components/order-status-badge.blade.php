@props(['status'])

@php
    $colors = [
        'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-950 dark:text-yellow-300',
        'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
        'indigo' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300',
        'green' => 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300',
        'red' => 'bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300',
        'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex rounded-full px-2.5 py-1 text-xs font-medium '.$colors[$status->color()]]) }}>
    {{ $status->label() }}
</span>
