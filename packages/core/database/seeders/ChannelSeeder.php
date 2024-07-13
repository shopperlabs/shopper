<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Core\Repositories\ChannelRepository;

final class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        (new ChannelRepository())->create([
            'name' => $name = 'Web Store',
            'slug' => $name,
            'url' => config('app.url'),
            'is_default' => true,
        ]);
    }
}
