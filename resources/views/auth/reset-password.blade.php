<x-layouts.guest title="Reset password">
    <h1 class="mb-6 text-xl font-semibold">Reset your password</h1>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-input name="email" type="email" label="Email" value="{{ old('email', $request->email) }}" required autofocus :error="$errors->first('email')" />
        <x-input name="password" type="password" label="New password" required :error="$errors->first('password')" />
        <x-input name="password_confirmation" type="password" label="Confirm new password" required :error="$errors->first('password_confirmation')" />

        <x-button class="w-full">Reset password</x-button>
    </form>
</x-layouts.guest>
