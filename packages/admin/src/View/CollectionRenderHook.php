<?php

declare(strict_types=1);

namespace Shopper\View;

final class CollectionRenderHook
{
    public const string INDEX_TABLE_BEFORE = 'shopper::collections.index.table.before';

    public const string INDEX_TABLE_AFTER = 'shopper::collections.index.table.after';

    public const string EDIT_FORM_BEFORE = 'shopper::collection.edit.form.before';

    public const string EDIT_FORM_AFTER = 'shopper::collection.edit.form.after';
}
