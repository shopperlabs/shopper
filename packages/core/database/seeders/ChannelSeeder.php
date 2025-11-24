<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Core\Models\Channel;

final class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        Channel::resolvedQuery()->create([
            'name' => $name = 'Web Store',
            'slug' => $name,
            'url' => config('app.url'),
            'is_default' => true,
            'is_enabled' => true,
        ]);
    }
}
