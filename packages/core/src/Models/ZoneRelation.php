<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class ZoneRelation extends Model
{
    public $timestamps = false;

    public function getTable(): string
    {
        return shopper_table('zone_has_relations');
    }

    public function zonable(): MorphTo
    {
        return $this->morphTo();
    }
}
