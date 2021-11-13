<?php

namespace Shopper\Framework\Http\Livewire\Discounts;

use Illuminate\Database\Eloquent\Collection;

trait WithDiscountAttributes
{
    public ?string $code = '';
    public string $type = 'percentage';
    public ?string $value = '';
    public string $apply = 'order';
    public string $minRequired = 'none'; // price, quantity
    public ?string $minRequiredValue = '';
    public bool $is_active = false;
    public string $eligibility = 'everyone';
    public bool $usage_number = false;
    public ?int $usage_limit = null;
    public bool $usage_limit_per_user = false;
    public string $date;
    public ?string $dateStart = null;
    public ?string $dateEnd = null;
    public string $searchProduct = '';
    public string $searchCustomer = '';
    public Collection $products;
    public Collection $customers;
    public array $productsDetails = [];
    public array $customersDetails = [];
    public array $productsIds = [];
    public array $customersIds = [];
    public array $selectedProducts = [];
    public array $selectedCustomers = [];

    public function generate()
    {
        $this->code = mb_substr(mb_strtoupper(uniqid(str_random(10))), 0, 10);
    }
}
