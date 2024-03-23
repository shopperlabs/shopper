<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\ReviewController;

Route::resource('reviews', ReviewController::class);
