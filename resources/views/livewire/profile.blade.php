<div class="max-w-2xl space-y-10">
    <h1 class="text-xl font-semibold">Account settings</h1>

    <section>
        <h2 class="mb-4 text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">Profile information</h2>

        <form wire:submit="updateProfileInformation" class="space-y-4 rounded-lg border border-gray-200 p-6 dark:border-gray-800">
            <x-input name="name" label="Name" wire:model="name" :error="$errors->first('name')" />
            <x-input name="email" type="email" label="Email" wire:model="email" :error="$errors->first('email')" />

            <x-button>Save</x-button>
        </form>
    </section>

    <section>
        <h2 class="mb-4 text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">Update password</h2>

        <form wire:submit="updatePassword" class="space-y-4 rounded-lg border border-gray-200 p-6 dark:border-gray-800">
            <x-input name="current_password" type="password" label="Current password" wire:model="current_password" :error="$errors->first('current_password')" />
            <x-input name="password" type="password" label="New password" wire:model="password" :error="$errors->first('password')" />
            <x-input name="password_confirmation" type="password" label="Confirm new password" wire:model="password_confirmation" :error="$errors->first('password_confirmation')" />

            <x-button>Update password</x-button>
        </form>
    </section>

    <section>
        <h2 class="mb-4 text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">Two-factor authentication</h2>

        <div class="space-y-4 rounded-lg border border-gray-200 p-6 dark:border-gray-800">
            @if ($user->hasEnabledTwoFactorAuthentication())
                <p class="text-sm text-green-700 dark:text-green-400">Two-factor authentication is enabled.</p>

                @if ($showingRecoveryCodes)
                    <div class="rounded-md bg-gray-50 p-4 dark:bg-gray-800">
                        <p class="mb-2 text-sm font-medium">Save these recovery codes somewhere safe:</p>
                        <ul class="grid grid-cols-2 gap-1 font-mono text-xs text-gray-600 dark:text-gray-400">
                            @foreach ($user->recoveryCodes() as $recoveryCode)
                                <li>{{ $recoveryCode }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (! $this->passwordRecentlyConfirmed())
                    <div class="flex items-end gap-3">
                        <div class="flex-1">
                            <x-input name="confirmable_password" type="password" label="Confirm your password to disable" wire:model="confirmable_password" :error="$errors->first('confirmable_password')" />
                        </div>
                        <x-button wire:click="confirmPasswordForSecurityAction" variant="secondary" type="button">Confirm</x-button>
                    </div>
                @else
                    <x-button wire:click="disableTwoFactorAuthentication" type="button" variant="danger">
                        Disable two-factor authentication
                    </x-button>
                @endif
            @else
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Add an extra layer of security by requiring an authentication code in addition to your password.
                </p>

                @if ($showingQrCode)
                    <div class="rounded-md bg-white p-4 dark:bg-gray-100">
                        {!! $user->twoFactorQrCodeSvg() !!}
                    </div>

                    <form wire:submit="confirmTwoFactorAuthentication" class="flex items-end gap-3">
                        <div class="flex-1">
                            <x-input name="code" label="Enter the 6-digit code" wire:model="code" inputmode="numeric" :error="$errors->first('code')" />
                        </div>
                        <x-button>Confirm</x-button>
                    </form>
                @elseif (! $this->passwordRecentlyConfirmed())
                    <div class="flex items-end gap-3">
                        <div class="flex-1">
                            <x-input name="confirmable_password" type="password" label="Confirm your password to continue" wire:model="confirmable_password" :error="$errors->first('confirmable_password')" />
                        </div>
                        <x-button wire:click="confirmPasswordForSecurityAction" variant="secondary" type="button">Confirm</x-button>
                    </div>
                @else
                    <x-button wire:click="enableTwoFactorAuthentication" type="button" variant="primary">Enable two-factor authentication</x-button>
                @endif
            @endif
        </div>
    </section>
</div>
