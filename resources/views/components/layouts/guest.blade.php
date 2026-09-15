@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name') }}</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="flex min-h-screen flex-col items-center justify-center bg-gray-100 px-4 py-12 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <a href="{{ route('home') }}" class="mb-6">
            <x-logo class="h-9" />
        </a>

        <div class="w-full max-w-md rounded-lg border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
