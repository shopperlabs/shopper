<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Http\Controllers\Ecommerce;

Route::resource('collections', Ecommerce\CollectionController::class);