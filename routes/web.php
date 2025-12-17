<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public Routes
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Customer Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\CustomerAuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [\App\Http\Controllers\CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\CustomerAuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [\App\Http\Controllers\CustomerAuthController::class, 'logout'])->name('logout')->middleware('auth');

// Cart Routes
Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::get('add-to-cart/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('update-cart', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('remove-from-cart', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('checkout', [\App\Http\Controllers\OrderController::class, 'store'])->name('checkout')->middleware('auth'); // Middleware auth required for checkout
Route::prefix('payment')->name('payment.')->middleware('auth')->group(function () {
    Route::get('/checkout/{order}', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/simulate', [\App\Http\Controllers\PaymentController::class, 'simulate'])->name('simulate');
    Route::post('/confirm', [\App\Http\Controllers\PaymentController::class, 'confirm'])->name('confirm');
    Route::get('/receipt/{order}', [\App\Http\Controllers\PaymentController::class, 'receipt'])->name('receipt');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('/login', [\App\Http\Controllers\AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [\App\Http\Controllers\AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('medicines', \App\Http\Controllers\MedicineController::class)->names([
        'index' => 'medicines.index',
        'create' => 'medicines.create',
        'store' => 'medicines.store',
        'edit' => 'medicines.edit',
        'update' => 'medicines.update',
        'destroy' => 'medicines.destroy',
    ]);

    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class)->names([
        'index' => 'suppliers.index',
        'create' => 'suppliers.create',
        'store' => 'suppliers.store',
        'edit' => 'suppliers.edit',
        'update' => 'suppliers.update',
        'destroy' => 'suppliers.destroy',
    ]);
    Route::get('suppliers/{supplier}/email', [\App\Http\Controllers\SupplierController::class, 'emailForm'])->name('suppliers.email');
    Route::post('suppliers/{supplier}/email', [\App\Http\Controllers\SupplierController::class, 'sendEmail'])->name('suppliers.email.send');


    Route::get('batches/alerts', [\App\Http\Controllers\BatchController::class, 'alerts'])->name('batches.alerts');
    Route::resource('batches', \App\Http\Controllers\BatchController::class)->names([
        'index' => 'batches.index',
        'create' => 'batches.create',
        'store' => 'batches.store',
        'edit' => 'batches.edit',
        'update' => 'batches.update',
        'destroy' => 'batches.destroy',
    ]);

    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{id}', [\App\Http\Controllers\OrderController::class, 'update'])->name('orders.update');

    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    
    Route::get('email-logs', [\App\Http\Controllers\EmailLogController::class, 'index'])->name('email-logs.index');
    Route::post('email-logs/clear', [\App\Http\Controllers\EmailLogController::class, 'clear'])->name('email-logs.clear');
    });
});

Route::prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SalesController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\SalesController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\SalesController::class, 'store'])->name('store');
    Route::get('/{id}', [\App\Http\Controllers\SalesController::class, 'show'])->name('show');
});

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/daily-sales', [\App\Http\Controllers\ReportController::class, 'dailySales'])->name('daily_sales');
    Route::get('/top-medicine', [\App\Http\Controllers\ReportController::class, 'topMedicines'])->name('top_medicine');
});
