<x-layouts.guest title="Log in">
    <h1 class="mb-6 text-xl font-semibold">Log in to your account</h1>

    @if (session('status'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-300">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-input name="email" type="email" label="Email" value="{{ old('email') }}" required autofocus :error="$errors->first('email')" />
        <x-input name="password" type="password" label="Password" required :error="$errors->first('password')" />

        <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            <input type="checkbox" name="remember" class="rounded border-gray-300">
            Remember me
        </label>

        <x-button class="w-full">Log in</x-button>

        <div class="flex items-center justify-between text-sm">
            <a href="{{ route('password.request') }}" class="text-brand-600 hover:underline dark:text-brand-400">Forgot password?</a>
            <a href="{{ route('register') }}" class="text-brand-600 hover:underline dark:text-brand-400">Create account</a>
        </div>
    </form>
</x-layouts.guest>
