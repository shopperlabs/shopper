<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Core\Models\Contracts\Channel as ChannelContract;

final class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        resolve(ChannelContract::class)::query()->create([
            'name' => $name = 'Web Store',
            'slug' => $name,
            'url' => config('app.url'),
            'is_default' => true,
            'is_enabled' => true,
        ]);
    }
}
