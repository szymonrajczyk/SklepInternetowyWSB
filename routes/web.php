<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AccountController;

Route::get('/', [ProductController::class, 'index'])->name('home');

Auth::routes();

Route::resource('products', ProductController::class);

Route::resource('categories', CategoryController::class);

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update.status');

Route::prefix('account')->name('account.')->middleware('auth')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/change-password', [AccountController::class, 'changePassword'])->name('change-password');
    Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('update-password');
    Route::get('/confirm-delete', [AccountController::class, 'confirmDelete'])->name('confirm-delete');
    Route::delete('/destroy', [AccountController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::resource('users', UserController::class);
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.users.index');
    } elseif ($user->isSeller()) {
        return redirect()->route('products.index', ['seller' => $user->id]);
    } else {
        return redirect()->route('orders.index');
    }
})->middleware('auth')->name('dashboard');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
