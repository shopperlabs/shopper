<?php

declare(strict_types=1);

namespace Shopper\Core\Sidebar\Domain;

use Maatwebsite\Sidebar\Domain\DefaultItem as BaseDefaultItem;

final class DefaultItem extends BaseDefaultItem
{
    /**
     * @var string
     */
    protected $icon = '';

    /**
     * @var string
     */
    protected $toggleIcon = '';
}
