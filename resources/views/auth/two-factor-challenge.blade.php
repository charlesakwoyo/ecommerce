<x-layouts.guest title="Two-factor challenge">
    <div x-data="{ useRecovery: false }">
        <h1 class="mb-2 text-xl font-semibold">Two-factor authentication</h1>
        <p class="mb-6 text-sm text-gray-600 dark:text-gray-400" x-show="! useRecovery">
            Enter the authentication code from your authenticator app.
        </p>
        <p class="mb-6 text-sm text-gray-600 dark:text-gray-400" x-show="useRecovery" x-cloak>
            Enter one of your emergency recovery codes.
        </p>

        <form method="POST" action="{{ route('two-factor.login.store') }}" class="space-y-4">
            @csrf

            <div x-show="! useRecovery">
                <x-input name="code" label="Authentication code" inputmode="numeric" autocomplete="one-time-code" autofocus :error="$errors->first('code')" />
            </div>

            <div x-show="useRecovery" x-cloak>
                <x-input name="recovery_code" label="Recovery code" autocomplete="off" :error="$errors->first('recovery_code')" />
            </div>

            <x-button class="w-full">Continue</x-button>

            <button type="button" class="w-full text-center text-sm text-indigo-600 hover:underline dark:text-indigo-400" @click="useRecovery = ! useRecovery">
                <span x-show="! useRecovery">Use a recovery code instead</span>
                <span x-show="useRecovery" x-cloak>Use an authentication code instead</span>
            </button>
        </form>
    </div>
</x-layouts.guest>
