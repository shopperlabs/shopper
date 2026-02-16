<?php

declare(strict_types=1);

namespace Shopper\Enum;

enum RenderHook: string
{
    case HeadStart = 'head.start';

    case HeadEnd = 'head.end';

    case BodyStart = 'body.start';

    case BodyEnd = 'body.end';

    case ContentStart = 'content.start';

    case ContentEnd = 'content.end';
}
