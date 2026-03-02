<?php

declare(strict_types=1);

namespace Shopper\View;

final class LayoutRenderHook
{
    public const string HEAD_START = 'shopper::head.start';

    public const string HEAD_END = 'shopper::head.end';

    public const string BODY_START = 'shopper::body.start';

    public const string BODY_END = 'shopper::body.end';

    public const string HEADER_START = 'shopper::header.start';

    public const string HEADER_END = 'shopper::header.end';

    public const string CONTENT_START = 'shopper::content.start';

    public const string CONTENT_END = 'shopper::content.end';

    public const string DASHBOARD_START = 'shopper::dashboard.start';

    public const string DASHBOARD_END = 'shopper::dashboard.end';

    public const string ACCOUNT_START = 'shopper::account.start';

    public const string ACCOUNT_END = 'shopper::account.end';

    public const string SETTINGS_INDEX_START = 'shopper::settings.index.start';

    public const string SETTINGS_INDEX_END = 'shopper::settings.index.end';
}
