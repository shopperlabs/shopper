<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Attribute;

class AttributeObserver
{
    public function deleting(Attribute $attribute): void
    {
        $attribute->products()->detach();
        $attribute->values()->delete();
    }
}
