<x-layouts.app title="Terms of Service">
    <div class="mx-auto max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Terms of Service</h1>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">Last updated: {{ now()->format('F Y') }}</p>

        <div class="mt-8 space-y-8 text-sm leading-relaxed text-gray-700 dark:text-gray-300">
            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">1. Acceptance of terms</h2>
                <p class="mt-2">
                    By accessing or using {{ config('app.name') }}, you agree to be bound by these Terms of
                    Service. If you do not agree, please do not use the site.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">2. Accounts</h2>
                <p class="mt-2">
                    You must provide accurate information when creating an account and are responsible for
                    keeping your login credentials secure. You are responsible for all activity that occurs
                    under your account.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">3. Orders and payment</h2>
                <p class="mt-2">
                    All prices are listed in Kenyan Shillings (KSh) and are subject to change without notice.
                    Placing an order is an offer to purchase; an order is only confirmed once payment has been
                    successfully processed. We reserve the right to refuse or cancel any order, including in
                    cases of suspected fraud or pricing errors.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">4. Product information</h2>
                <p class="mt-2">
                    We aim to display product information, including pricing and availability, as accurately
                    as possible. We do not warrant that product descriptions are error-free.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">5. Intellectual property</h2>
                <p class="mt-2">
                    All content on {{ config('app.name') }}, including text, graphics, logos and images, is the
                    property of {{ config('app.name') }} or its licensors and may not be reproduced without
                    permission.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">6. Limitation of liability</h2>
                <p class="mt-2">
                    {{ config('app.name') }} is provided "as is" without warranties of any kind. To the fullest
                    extent permitted by law, we are not liable for any indirect or consequential losses arising
                    from your use of the site.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">7. Changes to these terms</h2>
                <p class="mt-2">
                    We may update these terms from time to time. Continued use of the site after changes are
                    posted constitutes acceptance of the revised terms.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">8. Contact</h2>
                <p class="mt-2">
                    Questions about these terms can be sent via our
                    <a href="{{ route('pages.contact') }}" wire:navigate class="text-brand-600 hover:underline dark:text-brand-400">Contact page</a>.
                </p>
            </section>
        </div>
    </div>
</x-layouts.app>
