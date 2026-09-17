<x-layouts.app title="Help Center / FAQ">
    <div class="mx-auto max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Help Center</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Answers to common questions about shopping on {{ config('app.name') }}.</p>

        @php
            $faqs = [
                [
                    'q' => 'What payment methods do you accept?',
                    'a' => 'Checkout is processed securely through Stripe, which accepts major debit and credit cards. All prices are shown and charged in Kenyan Shillings (KSh).',
                ],
                [
                    'q' => 'Do I need an account to shop?',
                    'a' => 'You can browse products and build a cart as a guest. An account is required to complete checkout, so we can attach your order and shipping address to your order history.',
                ],
                [
                    'q' => 'How do I track my order?',
                    'a' => 'Once logged in, visit "My Orders" to see every order you have placed and its current status. You will be able to view an order as soon as your payment is confirmed.',
                ],
                [
                    'q' => 'Can I change or cancel an order after payment?',
                    'a' => 'Once an order is paid, it enters processing right away. Contact support as soon as possible if you need to make a change &mdash; we will do our best to help before it ships.',
                ],
                [
                    'q' => 'What is your returns policy?',
                    'a' => 'If an item arrives damaged, faulty, or different from what you ordered, contact support within 7 days of delivery with your order number and we will arrange a resolution.',
                ],
                [
                    'q' => 'What happens if an item goes out of stock?',
                    'a' => 'Stock is reserved the moment you complete checkout, so once your payment succeeds, your items are guaranteed. If a Stripe checkout session expires unpaid, any reserved stock is released automatically.',
                ],
            ];
        @endphp

        <div class="mt-8 divide-y divide-gray-200 dark:divide-gray-800">
            @foreach ($faqs as $faq)
                <details class="group py-4">
                    <summary class="flex cursor-pointer list-none items-center justify-between text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $faq['q'] }}
                        <span class="ml-4 shrink-0 text-gray-400 transition group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{!! $faq['a'] !!}</p>
                </details>
            @endforeach
        </div>

        <div class="mt-8 rounded-md border border-gray-200 p-5 text-sm text-gray-600 dark:border-gray-800 dark:text-gray-400">
            Still need help? <a href="{{ route('pages.contact') }}" wire:navigate class="text-brand-600 hover:underline dark:text-brand-400">Contact our support team</a>.
        </div>
    </div>
</x-layouts.app>
