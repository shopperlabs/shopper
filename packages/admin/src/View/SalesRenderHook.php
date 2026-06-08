<?php

declare(strict_types=1);

namespace Shopper\View;

final class SalesRenderHook
{
    public const string DISCOUNTS_TABLE_BEFORE = 'shopper::discounts.index.table.before';

    public const string DISCOUNTS_TABLE_AFTER = 'shopper::discounts.index.table.after';

    public const string DISCOUNT_CREATE_FORM_BEFORE = 'shopper::discounts.create.form.before';

    public const string DISCOUNT_CREATE_FORM_AFTER = 'shopper::discounts.create.form.after';

    public const string DISCOUNT_EDIT_FORM_BEFORE = 'shopper::discounts.edit.form.before';

    public const string DISCOUNT_EDIT_FORM_AFTER = 'shopper::discounts.edit.form.after';

    public const string SUPPLIERS_TABLE_BEFORE = 'shopper::suppliers.index.table.before';

    public const string SUPPLIERS_TABLE_AFTER = 'shopper::suppliers.index.table.after';
}
