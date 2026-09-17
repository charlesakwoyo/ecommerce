<x-layouts.app title="Privacy Policy">
    <div class="mx-auto max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Privacy Policy</h1>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">Last updated: {{ now()->format('F Y') }}</p>

        <div class="mt-8 space-y-8 text-sm leading-relaxed text-gray-700 dark:text-gray-300">
            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Information we collect</h2>
                <p class="mt-2">
                    When you create an account, place an order, or contact support, we collect information such
                    as your name, email address, phone number and shipping/billing address. We also store your
                    order history so you can review past purchases.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Payment information</h2>
                <p class="mt-2">
                    All payments are processed by Stripe. We do not collect or store your full card number,
                    expiry date or CVC on our servers &mdash; that information is handled directly by Stripe in
                    accordance with its own privacy and security practices.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Cookies and sessions</h2>
                <p class="mt-2">
                    We use session cookies to keep you logged in and to remember the contents of your shopping
                    cart while you browse, including as a guest before you create an account.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">How we use your information</h2>
                <p class="mt-2">
                    We use the information we collect to process and fulfil orders, communicate with you about
                    your account or orders, and improve the site. We do not sell your personal information to
                    third parties.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Your choices</h2>
                <p class="mt-2">
                    You can review and update your account details at any time from your Account settings. To
                    request deletion of your account or data, contact support.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Contact</h2>
                <p class="mt-2">
                    Questions about this policy can be sent via our
                    <a href="{{ route('pages.contact') }}" wire:navigate class="text-brand-600 hover:underline dark:text-brand-400">Contact page</a>.
                </p>
            </section>
        </div>
    </div>
</x-layouts.app>
