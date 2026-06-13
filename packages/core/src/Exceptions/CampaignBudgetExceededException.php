<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;

final class CampaignBudgetExceededException extends RuntimeException
{
    public static function make(string $name): self
    {
        return new self(__('shopper-core::exceptions.campaign_budget_exceeded', ['name' => $name]));
    }
}
