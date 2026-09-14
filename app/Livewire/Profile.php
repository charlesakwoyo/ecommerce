<?php

namespace App\Livewire;

use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Account settings')]
class Profile extends Component
{
    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $code = '';

    public bool $showingQrCode = false;

    public bool $showingRecoveryCodes = false;

    public string $confirmable_password = '';

    public function mount(): void
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function updateProfileInformation(UpdateUserProfileInformation $action): void
    {
        $action->update(auth()->user(), [
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('status', 'Profile updated.');
    }

    public function updatePassword(UpdateUserPassword $action): void
    {
        $action->update(auth()->user(), [
            'current_password' => $this->current_password,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('status', 'Password updated.');
    }

    public function confirmPasswordForSecurityAction(): void
    {
        $this->validate(['confirmable_password' => ['required', 'string']]);

        if (! Hash::check($this->confirmable_password, auth()->user()->password)) {
            $this->addError('confirmable_password', 'The password does not match our records.');

            return;
        }

        $this->reset('confirmable_password');
        session()->put('auth.password_confirmed_at', now()->unix());
    }

    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $action): void
    {
        if (! $this->passwordRecentlyConfirmed()) {
            return;
        }

        $action(auth()->user());

        $this->showingQrCode = true;
    }

    public function confirmTwoFactorAuthentication(ConfirmTwoFactorAuthentication $action): void
    {
        $action(auth()->user(), $this->code);

        $this->reset('code');
        $this->showingQrCode = false;
        $this->showingRecoveryCodes = true;
    }

    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $action): void
    {
        if (! $this->passwordRecentlyConfirmed()) {
            return;
        }

        $action(auth()->user());

        $this->showingQrCode = false;
        $this->showingRecoveryCodes = false;
    }

    public function passwordRecentlyConfirmed(): bool
    {
        $confirmedAt = session('auth.password_confirmed_at', 0);

        return (time() - $confirmedAt) < config('auth.password_timeout', 10800);
    }

    public function render(): View
    {
        /** @var User $user */
        $user = auth()->user()->fresh();

        return view('livewire.profile', [
            'user' => $user,
        ]);
    }
}
