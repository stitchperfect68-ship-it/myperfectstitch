<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin|staff'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/images', [ProductController::class, 'uploadImage'])->name('products.images.upload');
    Route::delete('products/{product}/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
    Route::post('products/{product}/images/reorder', [ProductController::class, 'reorderImages'])->name('products.images.reorder');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Gallery
    Route::resource('gallery', GalleryController::class);
    Route::post('gallery/bulk-upload', [GalleryController::class, 'bulkUpload'])->name('gallery.bulk-upload');

    // Portfolio
    Route::resource('portfolio', PortfolioController::class);
    Route::post('portfolio/{portfolio}/images', [PortfolioController::class, 'uploadImage'])->name('portfolio.images.upload');
    Route::delete('portfolio/{portfolio}/images/{image}', [PortfolioController::class, 'deleteImage'])->name('portfolio.images.delete');

    // Events
    Route::resource('events', EventController::class);
    Route::post('events/{event}/images', [EventController::class, 'uploadImage'])->name('events.images.upload');
    Route::delete('events/{event}/images/{image}', [EventController::class, 'deleteImage'])->name('events.images.delete');

    // Sliders
    Route::resource('sliders', SliderController::class);

    // Team
    Route::resource('team', TeamController::class);

    // Clients
    Route::resource('clients', ClientController::class);

    // Quotes
    Route::get('quotes', [QuoteController::class, 'index'])->name('quotes.index');
    Route::get('quotes/{quote}', [QuoteController::class, 'show'])->name('quotes.show');
    Route::put('quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
    Route::post('quotes/{quote}/send-payment-link', [QuoteController::class, 'sendPaymentLink'])->name('quotes.send-payment-link');
    Route::post('quotes/{quote}/convert-to-order', [QuoteController::class, 'convertToOrder'])->name('quotes.convert-to-order');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

    // Customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

    // Payments
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');

    // Reports (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        // Settings
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

        // Users
        Route::resource('users', UserController::class);
    });
});
