<?php

use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\Front\VerifyEmailController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\CurrencyConverterController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\OrdersController;
use App\Http\Controllers\Front\PaymentsController;
use App\Http\Controllers\Front\ProductsController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\StripeWebhooksController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    // 'prefix' => '{locale?}',
    // 'where' => ['locale' => 'en|es|fr|ar|in|cn'],
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect',
        'localeViewPath',

        // 'setLocale'
    ],
    // 'where' => ['locale' => '[a-z]{2}']
], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [ProductsController::class, 'show'])->name('products.show'); // {product:slug} 'slug' will be the default for [model binding] in this route instead of 'id'

    Route::resource('/cart', CartController::class);

    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store']);
    Route::get('checkout/{order}/payment', [PaymentsController::class, 'showPaymentForm'])->name('checkout.payment.create');
    Route::post('checkout/{order}/stripe/payment-intent', [PaymentsController::class, 'createStripePaymentIntent'])->name('stripe.paymentIntent.create');
    Route::get('checkout/{order}/payment/stripe/callback', [PaymentsController::class, 'confirm'])->name('stripe.return');

    Route::any('stripe/webhook', StripeWebhooksController::class); // except in 'app/Http/Middleware/VerifyCsrfToken.php'

    Route::post('/currency', [CurrencyConverterController::class, 'store'])->name('currency.store');

    Route::get('auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('auth.socialite.redirect');
    Route::get('auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('auth.socialite.callback');
    Route::get('auth/{provider}/user', [SocialController::class, 'show']); // for Test

    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth:web'])->group(function () {
        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::patch('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');

        // Two-Factor Authentication (Standalone Page - Requires Verified Email)
        Route::middleware(['verified'])->group(function () {
            Route::get('/user/2fa', [ProfileController::class, 'user2Fa'])->name('user.2fa');
            // Route::get('/user/2fa', function () {
            //     return view('front.user.2fa');
            // })->name('user.2fa');
        });
        // Route::middleware(['verified'])->group(function () {
        //     Route::view('/auth/user/2fa', 'front.auth.two-factor-auth')->name('front.2fa');
        // });
    });

    /*
    |--------------------------------------------------------------------------
    | Email Verification Routes (Already provided by Fortify)
    |--------------------------------------------------------------------------
    */

    // These are automatically registered by Fortify:
    // GET  /email/verify                    -> verification.notice
    // GET  /email/verify/{id}/{hash}        -> verification.verify  
    // POST /email/verification-notification -> verification.send

    // Additional OTP verification route
    Route::post('/email/verify/otp', [VerifyEmailController::class, 'verifyOtp'])->middleware(['auth:web', 'throttle:6,1'])
        ->name('verification.verify.otp');
});






require __DIR__ . '/admin_auth.php';

require __DIR__ . '/dashboard.php';


// For Test  ---------------- for me
Route::get('/cache', [TestController::class, 'cache']);
Route::get('/cache2', [TestController::class, 'cache2']);
Route::post('/paypal/webhook', function () {
    echo 'webhook Called';
});
// For Test  ---------------- for me


// Route::fallback(function () {
//     return redirect('/' . config('app.locale'));
// });


// For Testing [Development]
// =======================================================================================================

Route::get('routes-viewer', function () {
    $routes = collect(Route::getRoutes());
    return view('routes', compact('routes'));
});

// =======================================================================================================
