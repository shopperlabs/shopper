<?php

namespace Shopper\Framework\Sidebar\Domain;

use Maatwebsite\Sidebar\Domain\DefaultItem as BaseDefaultItem;

class DefaultItem extends BaseDefaultItem
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
