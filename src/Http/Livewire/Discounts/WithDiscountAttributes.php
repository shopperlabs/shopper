<?php

namespace Shopper\Framework\Http\Livewire\Discounts;

trait WithDiscountAttributes
{
    /**
     * Discount code.
     *
     * @var string|null
     */
    public ?string $code;

    /**
     * Discount coupon type.
     *
     * @var string
     */
    public string $type = 'percentage';

    /**
     * Value of the discount.
     *
     * @var string|null
     */
    public ?string $value;

    /**
     * Apply item of the discount. Can be order or product.
     *
     * @var string
     */
    public string $apply = 'order';

    /**
     * Minimum required to apply the discount coupon.
     *
     * @var string
     */
    public string $minRequired = 'none'; // price, quantity

    /**
     * Minimum value required to apply the discount coupon.
     *
     * @var string
     */
    public string $minRequiredValue;

    /**
     * Discount active status.
     *
     * @var bool
     */
    public bool $is_active = false;

    /**
     * @var string
     */
    public string $eligibility = 'everyone';

    /**
     * Discount usage limit number.
     *
     * @var bool
     */
    public bool $usage_number = false;

    /**
     * Coupon limit usage.
     *
     * @var int|null
     */
    public ?int $usage_limit = null;

    /**
     * Coupon limit user per user value.
     *
     * @var bool
     */
    public bool $usage_limit_per_user = false;

    /**
     * Global discount available date.
     *
     * @var string
     */
    public string $date;

    /**
     * Coupon start date.
     *
     * @var string
     */
    public string $dateStart;

    /**
     * Coupon end date.
     *
     * @var string
     */
    public string $dateEnd = '';

    /**
     * Search product input.
     *
     * @var string
     */
    public string $searchProduct = '';

    /**
     * Search customers attributes.
     *
     * @var string
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
     *
     * @var array
     */
    public array $productsDetails = [];

    /**
     * Customers details information.
     *
     * @var array
     */
    public array $customersDetails = [];

    /**
     * Products id.
     *
     * @var array
     */
    public array $productsIds = [];

    /**
     * Customers Id.
     *
     * @var array
     */
    public array $customersIds = [];

    /**
     * Selected products.
     *
     * @var array
     */
    public array $selectedProducts = [];

    /**
     * Selected customers that can apply the discount.
     *
     * @var array
     */
    public array $selectedCustomers = [];

    /**
     * Generate Discount code.
     */
    public function generate()
    {
        $this->code = substr(strtoupper(uniqid(str_random(10))), 0, 10);
    }
}
