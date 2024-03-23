<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\DiscountController;

Route::resource('discounts', DiscountController::class);
