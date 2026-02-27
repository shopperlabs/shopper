<?php

declare(strict_types=1);

namespace Shopper\View;

final class CatalogRenderHook
{
    public const CATEGORIES_TABLE_BEFORE = 'shopper::categories.index.table.before';

    public const CATEGORIES_TABLE_AFTER = 'shopper::categories.index.table.after';

    public const BRANDS_TABLE_BEFORE = 'shopper::brands.index.table.before';

    public const BRANDS_TABLE_AFTER = 'shopper::brands.index.table.after';

    public const TAGS_TABLE_BEFORE = 'shopper::tags.index.table.before';

    public const TAGS_TABLE_AFTER = 'shopper::tags.index.table.after';

    public const ATTRIBUTES_TABLE_BEFORE = 'shopper::attributes.index.table.before';

    public const ATTRIBUTES_TABLE_AFTER = 'shopper::attributes.index.table.after';

    public const REVIEWS_TABLE_BEFORE = 'shopper::reviews.index.table.before';

    public const REVIEWS_TABLE_AFTER = 'shopper::reviews.index.table.after';
}
