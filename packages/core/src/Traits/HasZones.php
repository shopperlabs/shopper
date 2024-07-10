<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Models\Zone;

trait HasZones
{
    public function zones(): MorphToMany
    {
        return $this->morphToMany(Zone::class, 'zonable', shopper_table('zone_has_relations'));
    }
}
