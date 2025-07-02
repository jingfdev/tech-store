<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

// Home route
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Register page
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Social authentication routes
Route::controller(SocialAuthController::class)->group(function () {
    Route::get('/auth/{provider}', 'redirectToProvider')->name('social.redirect');
    Route::get('/auth/{provider}/callback', 'handleProviderCallback')->name('social.callback');
});

// Registration routes
Route::controller(RegisterController::class)->group(function () {
    Route::post('/register/complete-social', 'completeSocialRegistration')->name('register.complete-social');
    Route::post('/register/cancel-social', 'cancelSocialRegistration')->name('register.cancel-social');
});

// Dashboard (protected route)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Test Stripe configuration (remove in production)
Route::get('/test-stripe', function () {
    try {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $account = \Stripe\Account::retrieve();
        return response()->json([
            'status' => 'success',
            'message' => 'Stripe connection successful!',
            'account_id' => $account->id
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Stripe error: ' . $e->getMessage()
        ]);
    }
});

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Category routes
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Cart routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
    
    // Checkout routes
    Route::post('/checkout', [CheckoutController::class, 'createSession'])->name('checkout.create');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
