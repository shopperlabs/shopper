<?php

declare(strict_types=1);

use Shopper\Http\Facades\ShopperApi;

ShopperApi::store(function (): void {
    require __DIR__.'/store/catalog.php';
    require __DIR__.'/store/geo.php';
    require __DIR__.'/store/auth.php';
    require __DIR__.'/store/cart.php';
    require __DIR__.'/store/order.php';
});

ShopperApi::authenticated(function (): void {
    require __DIR__.'/store/account.php';
});

ShopperApi::webhooks(function (): void {
    require __DIR__.'/store/payment.php';
});
