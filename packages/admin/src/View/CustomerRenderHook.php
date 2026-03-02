<?php

declare(strict_types=1);

namespace Shopper\View;

final class CustomerRenderHook
{
    public const string INDEX_TABLE_BEFORE = 'shopper::customers.index.table.before';

    public const string INDEX_TABLE_AFTER = 'shopper::customers.index.table.after';

    public const string CREATE_FORM_BEFORE = 'shopper::customer.create.form.before';

    public const string CREATE_FORM_AFTER = 'shopper::customer.create.form.after';

    public const string SHOW_HEADER_AFTER = 'shopper::customer.show.header.after';

    public const string SHOW_TABS_BEFORE = 'shopper::customer.show.tabs.before';

    public const string SHOW_TABS_END = 'shopper::customer.show.tabs.end';

    public const string SHOW_CONTENT_BEFORE = 'shopper::customer.show.content.before';

    public const string SHOW_CONTENT_AFTER = 'shopper::customer.show.content.after';
}
