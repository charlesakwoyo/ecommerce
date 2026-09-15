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
    <body class="min-h-screen bg-gray-100 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <div class="sticky top-0 z-40 shadow-md">
            <header class="bg-brand-500">
                <div class="border-b border-white/15">
                    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-1.5 text-xs text-white/90 sm:px-6">
                        <p class="hidden sm:block">Welcome to {{ config('app.name') }} &mdash; quality products, fast delivery.</p>

                        <div class="flex items-center gap-4">
                            @auth
                                @if (auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="hover:text-white">Admin</a>
                                @endif
                                <a href="{{ route('orders.index') }}" wire:navigate class="hover:text-white">My Orders</a>
                                <a href="{{ route('profile.edit') }}" wire:navigate class="hover:text-white">Account</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="hover:text-white">Log out</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" wire:navigate class="hover:text-white">Log in</a>
                                <a href="{{ route('register') }}" wire:navigate class="hover:text-white">Sign up</a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-6 gap-y-3 px-4 py-3 sm:px-6">
                    <a href="{{ route('home') }}" wire:navigate class="shrink-0">
                        <x-logo variant="inverted" class="h-9" />
                    </a>

                    <form action="{{ route('products.index') }}" method="GET" class="order-3 flex w-full basis-full sm:order-none sm:w-auto sm:max-w-sm sm:basis-auto sm:flex-1">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Search products..."
                            class="w-full min-w-0 rounded-l-md border-0 px-4 py-2 text-sm text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-navy-900 focus:outline-none"
                        >
                        <button type="submit" class="flex shrink-0 items-center rounded-r-md bg-navy-900 px-4 text-white hover:bg-navy-800">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </form>

                    <div class="ml-auto flex items-center gap-5 text-sm font-semibold text-white">
                        <a href="{{ route('products.index') }}" wire:navigate class="hidden hover:text-navy-900 sm:block">
                            Shop
                        </a>

                        <a href="{{ route('cart.show') }}" wire:navigate class="flex items-center gap-1.5 hover:text-navy-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path d="M2.25 3a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a2.25 2.25 0 0 0 2.175 1.67h7.478a2.25 2.25 0 0 0 2.169-1.641l1.687-5.913a.75.75 0 0 0-.72-.986H6.169l-.464-1.741A1.875 1.875 0 0 0 3.886 3H2.25Z" />
                                <path d="M8.25 21a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25ZM17.25 21a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" />
                            </svg>
                            <span>Cart</span>
                            @livewire('cart-indicator')
                        </a>
                    </div>
                </div>
            </header>

            @livewire('category-nav')
        </div>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6">
            @if (session('status'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-300">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="mt-16 bg-navy-950 text-gray-400">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
                <x-logo variant="inverted" class="h-8" />
                <p class="mt-4 max-w-md text-sm">
                    Quality products, simple checkout, fast shipping &mdash; everything you need, delivered to your door.
                </p>
            </div>
            <div class="border-t border-white/10 px-4 py-4 text-center text-xs sm:px-6">
                &copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
