<?php

declare(strict_types=1);

namespace Shopper\View;

final class SalesRenderHook
{
    public const DISCOUNTS_TABLE_BEFORE = 'shopper::discounts.index.table.before';

    public const DISCOUNTS_TABLE_AFTER = 'shopper::discounts.index.table.after';

    public const SUPPLIERS_TABLE_BEFORE = 'shopper::suppliers.index.table.before';

    public const SUPPLIERS_TABLE_AFTER = 'shopper::suppliers.index.table.after';
}
