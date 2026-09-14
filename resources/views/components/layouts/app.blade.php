@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name') }}</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <header class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight">
                    {{ config('app.name') }}
                </a>

                <nav class="flex flex-1 items-center justify-center gap-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Home</a>
                    <a href="{{ route('products.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Shop</a>
                </nav>

                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('cart.show') }}" class="relative inline-flex items-center gap-1 font-medium hover:text-indigo-600 dark:hover:text-indigo-400" wire:navigate>
                        <span>Cart</span>
                        @livewire('cart-indicator')
                    </a>

                    @auth
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Admin</a>
                        @endif
                        <a href="{{ route('orders.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">My Orders</a>
                        <a href="{{ route('profile.edit') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Account</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-md border border-gray-300 px-3 py-1.5 font-medium hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800">
                                Log out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-3 py-1.5 font-medium text-white hover:bg-indigo-500">
                            Sign up
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            @if (session('status'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-300">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="mt-16 border-t border-gray-200 py-8 text-center text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
            &copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.
        </footer>

        @livewireScripts
    </body>
</html>
