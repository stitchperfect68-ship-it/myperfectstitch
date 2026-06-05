<?php

use App\Http\Controllers\Auth\SupabaseAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContextController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ── Public pages ──────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/context', [ContextController::class, 'index'])->name('context');

// ── Shop ──────────────────────────────────────────────────────────────────────
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

// ── Supabase Auth sync ────────────────────────────────────────────────────────
Route::post('/auth/supabase/sync',    [SupabaseAuthController::class, 'sync'])->name('auth.supabase.sync')->middleware('supabase.auth');
Route::post('/auth/supabase/signout', [SupabaseAuthController::class, 'signout'])->name('auth.supabase.signout');
Route::post('/auth/token-login', [SupabaseAuthController::class, 'tokenLogin'])
    ->name('auth.token.login')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/auth/login',             fn() => view('auth.supabase-login'))->name('auth.login');
// OAuth redirect landing page — Supabase JS picks up the token from the URL fragment automatically
Route::get('/auth/callback',          fn() => view('auth.supabase-callback'))->name('auth.callback');

// ── Quote form (requires Supabase auth) ───────────────────────────────────────
Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store')->middleware('auth:customer');

// ── Cart ──────────────────────────────────────────────────────────────────────
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

// ── Checkout ──────────────────────────────────────────────────────────────────
Route::middleware('auth:customer')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});

// ── Payment ───────────────────────────────────────────────────────────────────
Route::get('/payment/pending/{ref}',  [PaymentController::class, 'pending'])->name('payment.pending');
Route::get('/payment/status/{ref}',   [PaymentController::class, 'status'])->name('payment.status');
Route::post('/payment/webhook',       [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ── Order tracking ────────────────────────────────────────────────────────────
Route::get('/orders/{ref}', [OrderController::class, 'show'])->name('orders.show');

// ── Customer portal ───────────────────────────────────────────────────────────
Route::prefix('account')->name('customer.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.post');
        Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.post');
    });

    Route::middleware('customer.auth')->group(function () {
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
        Route::get('/orders', [CustomerDashboard::class, 'orders'])->name('orders');
        Route::get('/orders/{ref}', [CustomerDashboard::class, 'showOrder'])->name('order.show');
        Route::get('/profile', [CustomerDashboard::class, 'profile'])->name('profile');
        Route::put('/profile', [CustomerDashboard::class, 'updateProfile'])->name('profile.update');
        Route::get('/profile-data', [CustomerDashboard::class, 'profileData'])->name('profile.data');
        Route::put('/profile-update-ajax', [CustomerDashboard::class, 'updateProfileAjax'])->name('profile.update.ajax');
    });
});

// ── Admin auth (Breeze) ───────────────────────────────────────────────────────
require __DIR__ . '/auth.php';
