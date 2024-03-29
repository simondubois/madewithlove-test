<?php

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

Route::get('/', function () {
    return view('home')->with('products', \App\Product::all());
});

Route::get('cart/show', 'CartController@show');
Route::put('cart/pay', 'CartController@pay');

Route::apiResource('cart_products', CartProductController::class)
    ->only(['store', 'update', 'destroy']);
