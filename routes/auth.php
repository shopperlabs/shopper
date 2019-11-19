<?php

/*
|--------------------------------------------------------------------------
| Auth Web Routes
|--------------------------------------------------------------------------
|
| Base authentication route
|
*/

Route::get('/login', 'LoginController@showLoginForm');
Route::post('/login', 'LoginController@login')->name('login');
Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset', 'ResetPasswordController@reset');
