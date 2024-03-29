<?php

declare(strict_types=1);

namespace Shopper\Observers;

use Shopper\Core\Repositories\Store\CategoryRepository;

class CategoryObserver
{
    public function creating($model): void
    {
        if ($model->parent_id) {
            $parent = (new CategoryRepository())
                ->getById((int) $model->parent_id, ['name', 'slug']);

            $model->slug = $parent->slug . '-' . $model->name;
        }
    }

    public function updating($model): void
    {
        if ($model->parent_id) {
            $parent = (new CategoryRepository())
                ->getById((int) $model->parent_id, ['name', 'slug']);

            $model->slug = $parent->slug . '-' . $model->name;
        }
    }
}
