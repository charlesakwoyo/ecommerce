<x-layouts.guest title="Forgot password">
    <h1 class="mb-2 text-xl font-semibold">Forgot your password?</h1>
    <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        Enter your email and we'll send you a password reset link.
    </p>

    @if (session('status'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-300">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <x-input name="email" type="email" label="Email" value="{{ old('email') }}" required autofocus :error="$errors->first('email')" />

        <x-button class="w-full">Send reset link</x-button>

        <p class="text-center text-sm">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline dark:text-indigo-400">Back to login</a>
        </p>
    </form>
</x-layouts.guest>
