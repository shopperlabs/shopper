<?php

declare(strict_types=1);

namespace Shopper\View;

final class ProductRenderHook
{
    public const INDEX_TABLE_BEFORE = 'shopper::products.index.table.before';

    public const INDEX_TABLE_AFTER = 'shopper::products.index.table.after';

    public const EDIT_HEADER_AFTER = 'shopper::product.edit.header.after';

    public const EDIT_TABS_BEFORE = 'shopper::product.edit.tabs.before';

    public const EDIT_TABS_END = 'shopper::product.edit.tabs.end';

    public const EDIT_CONTENT_BEFORE = 'shopper::product.edit.content.before';

    public const EDIT_CONTENT_AFTER = 'shopper::product.edit.content.after';

    public const VARIANT_HEADER_AFTER = 'shopper::product.variant.header.after';

    public const VARIANT_MAIN_AFTER = 'shopper::product.variant.main.after';

    public const VARIANT_SIDEBAR_AFTER = 'shopper::product.variant.sidebar.after';
}
