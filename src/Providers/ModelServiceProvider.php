<?php

namespace Shopper\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Shopper\Framework\Models\Shop\Channel;
use Illuminate\Database\Eloquent\Relations\Relation;

class ModelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Relation::morphMap([
            'brand' => config('shopper.system.models.brand'),
            'category' => config('shopper.system.models.category'),
            'collection' => config('shopper.system.models.collection'),
            'product' => config('shopper.system.models.product'),
            'channel' => Channel::class,
        ]);
    }
}
