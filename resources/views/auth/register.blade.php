<x-layouts.guest title="Create account">
    <h1 class="mb-6 text-xl font-semibold">Create your account</h1>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        <x-input name="name" label="Name" value="{{ old('name') }}" required autofocus :error="$errors->first('name')" />
        <x-input name="email" type="email" label="Email" value="{{ old('email') }}" required :error="$errors->first('email')" />
        <x-input name="password" type="password" label="Password" required :error="$errors->first('password')" />
        <x-input name="password_confirmation" type="password" label="Confirm password" required :error="$errors->first('password_confirmation')" />

        <x-button class="w-full">Create account</x-button>

        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
            Already have an account?
            <a href="{{ route('login') }}" class="text-brand-600 hover:underline dark:text-brand-400">Log in</a>
        </p>
    </form>
</x-layouts.guest>
