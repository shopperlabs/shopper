<?php

declare(strict_types=1);

namespace Shopper\View;

final class CustomerRenderHook
{
    public const INDEX_TABLE_BEFORE = 'shopper::customers.index.table.before';

    public const INDEX_TABLE_AFTER = 'shopper::customers.index.table.after';

    public const CREATE_FORM_BEFORE = 'shopper::customer.create.form.before';

    public const CREATE_FORM_AFTER = 'shopper::customer.create.form.after';

    public const SHOW_HEADER_AFTER = 'shopper::customer.show.header.after';

    public const SHOW_TABS_BEFORE = 'shopper::customer.show.tabs.before';

    public const SHOW_TABS_END = 'shopper::customer.show.tabs.end';

    public const SHOW_CONTENT_BEFORE = 'shopper::customer.show.content.before';

    public const SHOW_CONTENT_AFTER = 'shopper::customer.show.content.after';
}
