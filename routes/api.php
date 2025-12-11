<?php

use App\Http\Controllers\Api\AccessTokensController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// RESTful API

/**
 * GET      /products          // List all or paginated
 * GET      /products/{id}     // Show one product
 * POST     /products          // Create new product (body required)
 * PUT      /products/{id}     // Update full product (body required)
 * DELETE   /products/{id}     // Delete product
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
    return Auth::guard('sanctum')->user();
});

// apiResource() => [index, show, store, update, destroy]
Route::apiResource('/products', ProductsController::class);

Route::post('auth/access-tokens', [AccessTokensController::class, 'store'])->middleware('guest:sanctum');
Route::delete('auth/access-tokens/{token?}', [AccessTokensController::class, 'destroy'])->middleware('auth:sanctum'); // {token?} => optional parameter
