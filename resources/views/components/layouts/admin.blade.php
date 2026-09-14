@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? 'Admin' }} &middot; {{ config('app.name') }}</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <div class="flex min-h-screen">
            <aside class="hidden w-56 shrink-0 border-r border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 sm:block">
                <a href="{{ route('home') }}" class="mb-6 block text-lg font-semibold tracking-tight">
                    {{ config('app.name') }}
                </a>

                <nav class="flex flex-col gap-1 text-sm font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="rounded-md px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="rounded-md px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.products.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                        Products
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="rounded-md px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                        Categories
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="rounded-md px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                        Orders
                    </a>

                    <div class="my-3 border-t border-gray-200 dark:border-gray-800"></div>

                    <a href="{{ route('home') }}" class="rounded-md px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                        &larr; Back to store
                    </a>
                </nav>
            </aside>

            <div class="flex-1">
                <header class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900 sm:hidden">
                    <span class="text-lg font-semibold">{{ config('app.name') }} Admin</span>
                </header>

                <main class="mx-auto max-w-5xl px-6 py-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-300">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
