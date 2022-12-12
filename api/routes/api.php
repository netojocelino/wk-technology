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

Route::get('/customer/{id}', [
    Controllers\CustomerController::class,
    'getCustomer',
])->name('getCustomer');


Route::post('/product', [
    Controllers\ProductController::class,
    'postProduct',
])->name('postProduct');

Route::get('/products', [
    Controllers\ProductController::class,
    'getProducts',
])->name('getProducts');

Route::get('/product/{id}', [
    Controllers\ProductController::class,
    'getProduct',
])->name('getProduct');

Route::post('/order/sale', [
    Controllers\SalesOrderController::class,
    'postSalesOrder',
])->name('postSalesOrder');

Route::get('/order/sale/{id}', [
    Controllers\SalesOrderController::class,
    'getSalesOrder',
])->name('getSalesOrder');

Route::get('/order/sale', [
    Controllers\SalesOrderController::class,
    'getSalesOrders',
])->name('getSalesOrders');
