<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Shipping\WebhookController;

Route::post('/webhooks/shipping/{driver}', WebhookController::class);
