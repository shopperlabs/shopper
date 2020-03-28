<?php

/*
|--------------------------------------------------------------------------
| Backend Web Routes
|--------------------------------------------------------------------------
|
|
*/

Route::redirect('/', \Shopper\Framework\Shopper::prefix() . '/dashboard', 301);
Route::prefix('dashboard')->group(function() {
    Route::get('/', 'DashboardController@index')->name('dashboard');
});

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
