<?php

declare(strict_types=1);

namespace Shopper\View;

final class OrderRenderHook
{
    public const string INDEX_TABLE_BEFORE = 'shopper::orders.index.table.before';

    public const string INDEX_TABLE_AFTER = 'shopper::orders.index.table.after';

    public const string DETAIL_HEADER_AFTER = 'shopper::order.detail.header.after';

    public const string DETAIL_MAIN_BEFORE = 'shopper::order.detail.main.before';

    public const string DETAIL_MAIN_AFTER = 'shopper::order.detail.main.after';

    public const string DETAIL_SIDEBAR_BEFORE = 'shopper::order.detail.sidebar.before';

    public const string DETAIL_SIDEBAR_AFTER = 'shopper::order.detail.sidebar.after';

    public const string SHIPMENTS_TABLE_BEFORE = 'shopper::shipments.index.table.before';

    public const string SHIPMENTS_TABLE_AFTER = 'shopper::shipments.index.table.after';

    public const string ABANDONED_CARTS_TABLE_BEFORE = 'shopper::abandoned-carts.table.before';

    public const string ABANDONED_CARTS_TABLE_AFTER = 'shopper::abandoned-carts.table.after';
}
