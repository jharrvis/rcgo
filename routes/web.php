<?php

use App\Http\Controllers\Admin\ArticlePreviewController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/order/{order}/invoice', [App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download');
    Route::get('/admin/settings', \App\Livewire\Admin\Settings\Index::class)->name('admin.settings.index');
});


Route::get('/', [LandingPageController::class, 'index'])->name('home');

// Public article pages (server-rendered for SEO; drafts/scheduled 404 here)
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

// Checkout routes - with and without package slug
Route::get('/checkout-wizard/{package:slug?}', \App\Livewire\CheckoutWizard::class)->name('checkout.wizard');
Route::get('/checkout/{package:slug?}', function ($package = null) {
    return redirect()->route('checkout.wizard', ['package' => $package]);
})->name('checkout'); // Keep name for backward compat if needed, or update route name references
// Route::get('/checkout', \App\Livewire\Checkout::class)->name('cart'); // Old cart route
Route::get('/checkout', function () {
    return redirect()->route('checkout.wizard');
})->name('checkout.cart');

Route::get('/order/success/{order}', function (\App\Models\Order $order) {
    // Eager load relationships to prevent N+1 and null errors
    $order->load(['orderItems.package', 'orderItems.addon']);
    return view('order-success', compact('order'));
})->middleware(['auth'])->name('order.success');

Route::post('/webhooks/xendit', [\App\Http\Controllers\XenditController::class, 'handleWebhook'])->name('xendit.webhook');

Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
});

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::middleware(['auth'])->group(function () {

    // Admin Routes - Require 'admin' middleware
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');

        // Product Management
        Route::get('/admin/products/packages', \App\Livewire\Admin\Product\PackageIndex::class)->name('admin.packages.index');
        Route::get('/admin/products/packages/create', \App\Livewire\Admin\Product\PackageForm::class)->name('admin.packages.create');
        Route::get('/admin/products/packages/{package}/edit', \App\Livewire\Admin\Product\PackageForm::class)->name('admin.packages.edit');

        Route::get('/admin/products/addons', \App\Livewire\Admin\Product\AddonIndex::class)->name('admin.addons.index');
        Route::get('/admin/products/addons/create', \App\Livewire\Admin\Product\AddonForm::class)->name('admin.addons.create');
        Route::get('/admin/products/addons/{addon}/edit', \App\Livewire\Admin\Product\AddonForm::class)->name('admin.addons.edit');

        // Article Management
        Route::get('/admin/articles', \App\Livewire\Admin\Article\ArticleIndex::class)->name('admin.articles.index');
        Route::get('/admin/articles/create', \App\Livewire\Admin\Article\ArticleForm::class)->name('admin.articles.create');
        Route::get('/admin/articles/{article}/edit', \App\Livewire\Admin\Article\ArticleForm::class)->name('admin.articles.edit');
        Route::get('/admin/articles/{article:id}/preview', ArticlePreviewController::class)->name('admin.articles.preview');

        // Order Management
        Route::get('/admin/orders', \App\Livewire\Admin\Order\OrderIndex::class)->name('admin.orders.index');
        Route::get('/admin/orders/{order}', \App\Livewire\Admin\Order\OrderDetail::class)->name('admin.orders.show');

        // Partner Management
        Route::get('/admin/partners', \App\Livewire\Admin\Partner\PartnerIndex::class)->name('admin.partners.index');
        Route::get('/admin/partners/{partnership}', \App\Livewire\Admin\Partner\PartnerDetail::class)->name('admin.partners.show');

        // Shipping Rate Management
        Route::get('/admin/settings/shipping', \App\Livewire\Admin\Setting\ShippingRateIndex::class)->name('admin.settings.shipping.index');
        Route::get('/admin/settings/shipping/create', \App\Livewire\Admin\Setting\ShippingRateForm::class)->name('admin.settings.shipping.create');
        Route::get('/admin/settings/shipping/{rate}/edit', \App\Livewire\Admin\Setting\ShippingRateForm::class)->name('admin.settings.shipping.edit');

        // Admin Profile Settings
        Route::get('/admin/profile', \App\Livewire\Admin\Settings\ProfileForm::class)->name('admin.profile');

        // User / Role Management
        Route::get('/admin/users', \App\Livewire\Admin\User\UserIndex::class)->name('admin.users.index');
        Route::get('/admin/users/create', \App\Livewire\Admin\User\UserForm::class)->name('admin.users.create');
        Route::get('/admin/users/{user}/edit', \App\Livewire\Admin\User\UserForm::class)->name('admin.users.edit');
    });

    // Mitra / General Auth Routes - Grouped for organization (Middleware already applies)
    Route::group(['prefix' => 'mitra', 'as' => 'mitra.'], function () {
        Route::get('/', \App\Livewire\Mitra\Dashboard::class)->name('dashboard');
        Route::get('/orders', \App\Livewire\Mitra\Order\OrderIndex::class)->name('orders.index');
        Route::get('/orders/{order}', \App\Livewire\Mitra\Order\OrderDetail::class)->name('orders.show');
        Route::get('/settings', \App\Livewire\Mitra\Settings\ProfileForm::class)->name('settings');
    });
});