<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Shopper Store API routes
|--------------------------------------------------------------------------
|
| Endpoints are added per domain group (catalog, auth, account, checkout,
| payment, shipping) in their own branches. Register them through the
| ShopperApi facade so they inherit the store prefix, JSON:API response,
| zone resolution, and rate limiting from shopper/http:
|
|   use Shopper\Http\Facades\ShopperApi;
|
|   ShopperApi::store(function (): void {
|       Route::get('/products', [ProductController::class, 'index']);
|   });
|
*/
