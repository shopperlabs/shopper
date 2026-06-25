<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Api\Http\Controllers\Payment\WebhookController;

Route::post('/webhooks/{driver}', WebhookController::class);
