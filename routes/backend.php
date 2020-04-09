<?php

use Illuminate\Support\Facades\Route;
use Shopper\Framework\Shopper;

/*
|--------------------------------------------------------------------------
| Backend Web Routes
|--------------------------------------------------------------------------
|
|
*/

Route::redirect('/', Shopper::prefix() . '/dashboard', 301);
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::prefix('access')->group(function () {
    Route::get('users', 'UserController@index')->name('users.access');
});

Route::group(['prefix' => 'shop', 'as' => 'shop.'], function () {
    Route::get('/setting', 'ShopController@setting')->name('setting');
});

Route::namespace('Ecommerce')->group(function () {

    Route::resource('categories', 'CategoryController');
    Route::resource('brands', 'BrandController');
    Route::resource('collections', 'CollectionController');

});
