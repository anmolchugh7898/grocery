<?php

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

Route::post('addProducts', 'App\Http\Controllers\Api\ProductController@addProducts');
Route::post('addProductImages', 'App\Http\Controllers\Api\ProductController@addProductImages');
Route::post('addCategory', 'App\Http\Controllers\Api\ProductController@addCategory');
Route::get('getCategory', 'App\Http\Controllers\Api\ProductController@getCategoryList');
Route::get('getProductList', 'App\Http\Controllers\Api\ProductController@getProductList');
Route::get('getProductbyCategory', 'App\Http\Controllers\Api\ProductController@getProductbyCategory');
