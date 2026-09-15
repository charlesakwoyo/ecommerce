@props(['variant' => 'default', 'class' => 'h-8'])

@php
    $inverted = $variant === 'inverted';
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-2', $class]) }}>
    <span class="flex aspect-square h-full items-center justify-center rounded-lg {{ $inverted ? 'bg-white' : 'bg-navy-900' }}">
        <svg viewBox="0 0 24 24" class="h-2/3 w-2/3 {{ $inverted ? 'text-brand-600' : 'text-brand-500' }}" fill="currentColor" aria-hidden="true">
            <path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z" />
        </svg>
    </span>
    <span class="text-xl leading-none font-extrabold tracking-tight uppercase">
        <span class="{{ $inverted ? 'text-white' : 'text-navy-900 dark:text-white' }}">Swift</span><span class="{{ $inverted ? 'text-white' : 'text-brand-500' }}">Hub</span>
    </span>
</span>
