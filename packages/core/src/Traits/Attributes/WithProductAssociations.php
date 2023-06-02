<?php

declare(strict_types=1);

namespace Shopper\Core\Traits\Attributes;

trait WithProductAssociations
{
    public array $category_ids = [];

    public array $collection_ids = [];

    public array $associateCollections = [];
}
