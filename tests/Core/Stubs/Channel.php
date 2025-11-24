<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Shopper\Core\Models\Channel as BaseChannel;

final class Channel extends BaseChannel
{
    public function customMethod(): string
    {
        return 'custom-method-called';
    }
}
