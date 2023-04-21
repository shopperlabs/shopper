<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Discounts;

use Illuminate\Database\Eloquent\Collection;

trait WithDiscountAttributes
{
    public ?string $code = '';

    public ?int $value = null;

    public ?string $minRequiredValue = '';

    public ?string $dateStart = null;

    public ?string $dateEnd = null;

    public ?int $usage_limit = null;

    public string $type = 'percentage';

    public string $apply = 'order';

    public string $minRequired = 'none'; // price, quantity

    public bool $is_active = false;

    public string $eligibility = 'everyone';

    public bool $usage_number = false;

    public bool $usage_limit_per_user = false;

    public string $date;

    public Collection $products;

    public Collection $customers;

    public array $selectedProducts = [];

    public array $selectedCustomers = [];

    public function generate(): void
    {
        $this->code = mb_substr(mb_strtoupper(uniqid(str_random(10))), 0, 10);
    }
}
