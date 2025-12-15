<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\CurrencyConverterController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductsController;
use App\Http\Controllers\ProfileController;
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

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store']);

    Route::view('/auth/user/2fa', 'front.auth.two-factor-auth')->name('front.2fa')->middleware('auth');

    Route::post('/currency', [CurrencyConverterController::class, 'store'])->name('currency.store');
});


Route::get('/cache', [TestController::class, 'cache']);

// For Test  ----------------
Route::post('/paypal/webhook', function () {
    echo 'webhook Called';
});
// For Test  ----------------

// From Laravel Breeze:------------------------
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
// require __DIR__ . '/auth.php';
// From Laravel Breeze:------------------------

require __DIR__ . '/dashboard.php';

// Route::fallback(function () {
//     return redirect('/' . config('app.locale'));
// });



// For Testing [Development]
// =======================================================================================================
Route::get('/route-viewer', function () {
    $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'middleware' => implode(', ', $route->middleware()),
        ];
    });

    return view('route-viewer', compact('routes'));
});
// =======================================================================================================
// =======================================================================================================

Route::get('/routes-viewer', function () {
    $routes = collect(Route::getRoutes());
    return view('routes', compact('routes'));
});

// =======================================================================================================
