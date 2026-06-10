<?php

declare(strict_types=1);

use Shopper\Http\Facades\ShopperApi;

ShopperApi::store(function (): void {
    require __DIR__.'/store/catalog.php';
});
