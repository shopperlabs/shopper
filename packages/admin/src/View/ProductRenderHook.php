<?php

declare(strict_types=1);

namespace Shopper\View;

final class ProductRenderHook
{
    public const string INDEX_TABLE_BEFORE = 'shopper::products.index.table.before';

    public const string INDEX_TABLE_AFTER = 'shopper::products.index.table.after';

    public const string EDIT_HEADER_AFTER = 'shopper::product.edit.header.after';

    public const string EDIT_TABS_BEFORE = 'shopper::product.edit.tabs.before';

    public const string EDIT_TABS_END = 'shopper::product.edit.tabs.end';

    public const string EDIT_CONTENT_BEFORE = 'shopper::product.edit.content.before';

    public const string EDIT_CONTENT_AFTER = 'shopper::product.edit.content.after';

    public const string VARIANT_HEADER_AFTER = 'shopper::product.variant.header.after';

    public const string VARIANT_MAIN_AFTER = 'shopper::product.variant.main.after';

    public const string VARIANT_SIDEBAR_AFTER = 'shopper::product.variant.sidebar.after';
}
