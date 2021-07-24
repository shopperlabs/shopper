<?php

namespace Shopper\Framework\Http\Livewire\Discounts;

trait WithDiscountAttributes
{
    /**
     * Discount code.
     */
    public ?string $code;

    /**
     * Discount coupon type.
     */
    public string $type = 'percentage';

    /**
     * Value of the discount.
     */
    public ?string $value;

    /**
     * Apply item of the discount. Can be order or product.
     */
    public string $apply = 'order';

    /**
     * Minimum required to apply the discount coupon.
     */
    public string $minRequired = 'none'; // price, quantity

    /**
     * Minimum value required to apply the discount coupon.
     */
    public string $minRequiredValue;

    /**
     * Discount active status.
     */
    public bool $is_active = false;

    public string $eligibility = 'everyone';

    /**
     * Discount usage limit number.
     */
    public bool $usage_number = false;

    /**
     * Coupon limit usage.
     */
    public ?int $usage_limit = null;

    /**
     * Coupon limit user per user value.
     */
    public bool $usage_limit_per_user = false;

    /**
     * Global discount available date.
     */
    public string $date;

    /**
     * Coupon start date.
     */
    public string $dateStart;

    /**
     * Coupon end date.
     */
    public string $dateEnd = '';

    /**
     * Search product input.
     */
    public string $searchProduct = '';

    /**
     * Search customers attributes.
     */
    public string $searchCustomer = '';

    /**
     * Products collections items.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $products;

    /**
     * Customers collections items.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $customers;

    /**
     * Products details.
     */
    public array $productsDetails = [];

    /**
     * Customers details information.
     */
    public array $customersDetails = [];

    /**
     * Products id.
     */
    public array $productsIds = [];

    /**
     * Customers Id.
     */
    public array $customersIds = [];

    /**
     * Selected products.
     */
    public array $selectedProducts = [];

    /**
     * Selected customers that can apply the discount.
     */
    public array $selectedCustomers = [];

    /**
     * Generate Discount code.
     */
    public function generate()
    {
        $this->code = mb_substr(mb_strtoupper(uniqid(str_random(10))), 0, 10);
    }
}
