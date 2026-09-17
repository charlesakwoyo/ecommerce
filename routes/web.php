<?php

use App\Http\Controllers\MpesaCallbackController;
use App\Http\Controllers\StripeWebhookController;
use App\Livewire\Admin\CategoryIndex as AdminCategoryIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\OrderIndex as AdminOrderIndex;
use App\Livewire\Admin\OrderShow as AdminOrderShow;
use App\Livewire\Admin\ProductForm as AdminProductForm;
use App\Livewire\Admin\ProductIndex as AdminProductIndex;
use App\Livewire\Profile;
use App\Livewire\Storefront\Cart;
use App\Livewire\Storefront\Checkout;
use App\Livewire\Storefront\Home;
use App\Livewire\Storefront\OrderIndex;
use App\Livewire\Storefront\OrderShow;
use App\Livewire\Storefront\ProductIndex;
use App\Livewire\Storefront\ProductShow;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/products', ProductIndex::class)->name('products.index');
Route::get('/products/{product:slug}', ProductShow::class)->name('products.show');

Route::get('/cart', Cart::class)->name('cart.show');

Route::view('/about', 'pages.about')->name('pages.about');
Route::view('/contact', 'pages.contact')->name('pages.contact');
Route::view('/faq', 'pages.faq')->name('pages.faq');
Route::view('/terms', 'pages.terms')->name('pages.terms');
Route::view('/privacy', 'pages.privacy')->name('pages.privacy');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', Checkout::class)->name('checkout.show');
    Route::get('/orders', OrderIndex::class)->name('orders.index');
    Route::get('/orders/{order}', OrderShow::class)->name('orders.show');
    Route::get('/profile', Profile::class)->name('profile.edit');
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('cashier.webhook');
Route::post('/mpesa/callback', [MpesaCallbackController::class, 'handle'])->name('mpesa.callback');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');

    Route::get('/products', AdminProductIndex::class)->name('products.index');
    Route::get('/products/create', AdminProductForm::class)->name('products.create');
    Route::get('/products/{product}/edit', AdminProductForm::class)->name('products.edit');

    Route::get('/categories', AdminCategoryIndex::class)->name('categories.index');

    Route::get('/orders', AdminOrderIndex::class)->name('orders.index');
    Route::get('/orders/{order}', AdminOrderShow::class)->name('orders.show');
});
