<x-layouts.guest title="Confirm password">
    <h1 class="mb-2 text-xl font-semibold">Confirm your password</h1>
    <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        This is a sensitive action, please confirm your password to continue.
    </p>

    <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-4">
        @csrf

        <x-input name="password" type="password" label="Password" required autofocus :error="$errors->first('password')" />

        <x-button class="w-full">Confirm</x-button>
    </form>
</x-layouts.guest>
