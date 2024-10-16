<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductRelation extends Model
{
    public $timestamps = false;

    public function getTable(): string
    {
        return shopper_table('product_has_relations');
    }

    public function productable(): MorphTo
    {
        return $this->morphTo();
    }
}
