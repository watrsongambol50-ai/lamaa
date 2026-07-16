<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PricesController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminPackageController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminPosController;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding');
Route::get('/prices', [PricesController::class, 'index'])->name('prices');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/rate', [OrderController::class, 'rate'])->name('orders.rate');
    Route::post('/orders/{id}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('orders.confirm');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{id}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::get('/orders/{id}/pay/callback', [OrderController::class, 'paymentCallback'])->name('orders.pay.callback');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');

    // Loyalty
    Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('loyalty');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // The following are restricted to the full admin only — staff cannot manage
    // pricing, coupons, packages, other staff accounts, or view financial reports.
    Route::middleware('super_admin')->group(function () {
        // Price / Service management
        Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::get('/services/create', [AdminServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{id}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{id}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{id}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

        // Coupons
        Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
        Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
        Route::post('/coupons/{id}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');
        Route::delete('/coupons/{id}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

        // Packages
        Route::get('/packages', [AdminPackageController::class, 'index'])->name('packages.index');
        Route::post('/packages', [AdminPackageController::class, 'store'])->name('packages.store');
        Route::post('/packages/{id}/toggle', [AdminPackageController::class, 'toggle'])->name('packages.toggle');
        Route::delete('/packages/{id}', [AdminPackageController::class, 'destroy'])->name('packages.destroy');

        // Staff / Sub-admins
        Route::get('/staff', [AdminStaffController::class, 'index'])->name('staff.index');
        Route::post('/staff', [AdminStaffController::class, 'store'])->name('staff.store');
        Route::delete('/staff/{id}', [AdminStaffController::class, 'destroy'])->name('staff.destroy');

        // Financial reports
        Route::get('/reports', [\App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('reports.index');
    });

    // Admin profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Admin notifications
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/clear-all', [AdminNotificationController::class, 'clearAll'])->name('notifications.clearAll');

    // Cashier / POS
    Route::get('/pos', [AdminPosController::class, 'create'])->name('pos.create');
    Route::post('/pos', [AdminPosController::class, 'store'])->name('pos.store');
});
