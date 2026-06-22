<?php

declare(strict_types=1);

namespace Shopper\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

trait ResolvesAdministrators
{
    /**
     * @return Collection<int, Model>
     */
    protected function administrators(): Collection
    {
        $userModel = config('auth.providers.users.model');

        $ids = Cache::remember(
            'shopper.notifications.administrator_ids',
            now()->addHours(2),
            fn (): array => $userModel::query()->administrators()->pluck('id')->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return $userModel::query()->whereKey($ids)->get();
    }
}
