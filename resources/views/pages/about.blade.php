<x-layouts.app title="About Us">
    <div class="mx-auto max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">About {{ config('app.name') }}</h1>

        <div class="mt-6 space-y-4 text-sm leading-relaxed text-gray-700 dark:text-gray-300">
            <p>
                {{ config('app.name') }} is an online storefront built for the Kenyan market, offering a wide
                range of products &mdash; from phones and electronics to fashion, home essentials and everyday
                groceries &mdash; at honest prices in Kenyan Shillings.
            </p>
            <p>
                Our goal is simple: make it easy to find what you need, check out quickly, and track your
                order from purchase to delivery. Every product on {{ config('app.name') }} is organised into
                clear categories, with search and filtering to help you find exactly what you're looking for.
            </p>
            <p>
                Payments are processed securely through Stripe, and every order is confirmed automatically
                once payment succeeds &mdash; no manual steps, no delays.
            </p>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-md border border-gray-200 p-4 text-center dark:border-gray-800">
                <p class="text-2xl font-extrabold text-brand-600 dark:text-brand-400">8+</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Product categories</p>
            </div>
            <div class="rounded-md border border-gray-200 p-4 text-center dark:border-gray-800">
                <p class="text-2xl font-extrabold text-brand-600 dark:text-brand-400">KSh</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Local currency pricing</p>
            </div>
            <div class="rounded-md border border-gray-200 p-4 text-center dark:border-gray-800">
                <p class="text-2xl font-extrabold text-brand-600 dark:text-brand-400">24/7</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Online ordering</p>
            </div>
        </div>
    </div>
</x-layouts.app>
