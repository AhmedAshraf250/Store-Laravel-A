<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfileController;
use Illuminate\Support\Facades\Route;


//Route::resource('dashboard/categories', CategoriesController::class)->middleware(['auth'])
//->names([
//    'index' => 'dashboard.categories.index',
//    'create' => 'dashboard.categories.create',
//    .....
//]);

Route::group([
    'middleware' => ['auth', 'auth.type:super-admin,admin'],
    'as' => 'dashboard.',
    'prefix' => 'dashboard',
    // 'namespace' => 'App\Http\Controllers\Dashboard',
], function () {
    //    Route::get('/', ['DashboardController@index']);
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // [CategoriesController@show] overwrite  to  [CategoriesController@trash] works 
    // Route::get('/categories/{category}', [CategoriesController::class, 'show'])->name('categories.show')->where('category', '\d+'); // ->`where('category', '\d+')` is a 'route constraint' that uses a regular expression to enforce that the `category` parameter must consist of one or more digits.
    Route::get('/categories/trash', [CategoriesController::class, 'trash'])->name('categories.trash');
    Route::put('/categories/{category}/restore', [CategoriesController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{category}/force-delete', [CategoriesController::class, 'forceDelete'])->name('categories.force-delete');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update'); // ['patch'=> with or when no parameter exist in the Route ,'put'=> when parameter is exist]

    Route::resource('/products', ProductsController::class);
    Route::resource('/categories', CategoriesController::class);
});
    //Route::middleware('auth')->as('dashboard')->prefix('dashboard')->group(function () {});
