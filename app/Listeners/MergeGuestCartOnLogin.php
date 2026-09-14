<?php

namespace App\Listeners;

use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class MergeGuestCartOnLogin
{
    public function __construct(private CartService $cart) {}

    public function handle(Login $event): void
    {
        $this->cart->mergeGuestCartIntoUser($event->user);
    }
}
