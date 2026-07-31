<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Catalog\AttributeController;
use Shopper\Api\Http\Controllers\Catalog\BrandController;
use Shopper\Api\Http\Controllers\Catalog\CategoryController;
use Shopper\Api\Http\Controllers\Catalog\CollectionController;
use Shopper\Api\Http\Controllers\Catalog\ProductController;
use Shopper\Api\Http\Controllers\Catalog\ReviewController;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/products/{slug}/reviews', [ReviewController::class, 'index']);
Route::get('/reviews', [ReviewController::class, 'latest']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);
Route::get('/collections', [CollectionController::class, 'index']);
Route::get('/collections/{slug}', [CollectionController::class, 'show']);
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{slug}', [BrandController::class, 'show']);
Route::get('/attributes', [AttributeController::class, 'index']);
