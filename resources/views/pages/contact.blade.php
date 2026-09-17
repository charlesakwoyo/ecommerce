<x-layouts.app title="Contact Us">
    <div class="mx-auto max-w-2xl">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Contact Us</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Have a question about an order, a product, or anything else? Reach out and we'll get back to you.
        </p>

        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-md border border-gray-200 p-5 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Customer support</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">support@swifthub.example</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">Monday &ndash; Saturday, 8am &ndash; 6pm EAT</p>
            </div>

            <div class="rounded-md border border-gray-200 p-5 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Order questions</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Already placed an order? Check its status any time from
                    <a href="{{ route('orders.index') }}" wire:navigate class="text-brand-600 hover:underline dark:text-brand-400">My Orders</a>.
                </p>
            </div>
        </div>

        <div class="mt-8 rounded-md border border-gray-200 p-5 text-sm text-gray-600 dark:border-gray-800 dark:text-gray-400">
            Before reaching out, you may find your answer faster in our
            <a href="{{ route('pages.faq') }}" wire:navigate class="text-brand-600 hover:underline dark:text-brand-400">Help Center / FAQ</a>.
        </div>
    </div>
</x-layouts.app>
