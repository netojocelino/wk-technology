<?php

use App\Http\Controllers;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/customer', [
    Controllers\CustomerController::class, 'postCustomer',
])->name('postCustomer');

Route::get('/customers', [
    Controllers\CustomerController::class,
    'getCustomers',
])->name('getCustomers');

Route::post('/product', [
    Controllers\ProductController::class,
    'postProduct',
])->name('postProduct');

Route::get('/products', [
    Controllers\ProductController::class,
    'getProducts',
])->name('getProducts');
