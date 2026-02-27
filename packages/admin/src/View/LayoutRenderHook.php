<?php

declare(strict_types=1);

namespace Shopper\View;

final class LayoutRenderHook
{
    public const HEAD_START = 'shopper::head.start';

    public const HEAD_END = 'shopper::head.end';

    public const BODY_START = 'shopper::body.start';

    public const BODY_END = 'shopper::body.end';

    public const HEADER_START = 'shopper::header.start';

    public const HEADER_END = 'shopper::header.end';

    public const CONTENT_START = 'shopper::content.start';

    public const CONTENT_END = 'shopper::content.end';

    public const DASHBOARD_START = 'shopper::dashboard.start';

    public const DASHBOARD_END = 'shopper::dashboard.end';

    public const ACCOUNT_START = 'shopper::account.start';

    public const ACCOUNT_END = 'shopper::account.end';

    public const SETTINGS_INDEX_START = 'shopper::settings.index.start';

    public const SETTINGS_INDEX_END = 'shopper::settings.index.end';
}
