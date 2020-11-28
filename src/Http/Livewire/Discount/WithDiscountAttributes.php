<?php

namespace Shopper\Framework\Http\Livewire\Discount;

trait WithDiscountAttributes
{
    /**
     * Discount code.
     *
     * @var string
     */
    public $code;

    /**
     * Discount coupon type.
     *
     * @var string
     */
    public $type = 'percentage';

    /**
     * Value of the discount.
     *
     * @var string
     */
    public $value;

    /**
     * Apply item of the discount. Can be order or product.
     *
     * @var string
     */
    public $apply = 'order';

    /**
     * Minimum required to apply the discount coupon.
     *
     * @var string
     */
    public $minRequired = 'none'; // price, quantity

    /**
     * Minimum value required to apply the discount coupon.
     *
     * @var string
     */
    public $minRequiredValue;

    /**
     * Discount active status.
     *
     * @var bool
     */
    public $is_active = false;

    /**
     * @var string
     */
    public $eligibility = 'everyone';

    /**
     * Discount usage limit number.
     *
     * @var bool
     */
    public $usage_number = false;

    /**
     * Coupon limit usage.
     *
     * @var int
     */
    public $usage_limit;

    /**
     * Coupon limit user per user value.
     *
     * @var bool
     */
    public $usage_limit_per_user = false;

    /**
     * Global discount available date.
     *
     * @var string
     */
    public $date;

    /**
     * Coupon start date.
     *
     * @var string
     */
    public $dateStart;

    /**
     * Coupon end date.
     *
     * @var string
     */
    public $dateEnd;

    /**
     * Search product input.
     *
     * @var string
     */
    public $searchProduct;

    /**
     * Search customers attributes.
     *
     * @var string
     */
    public $searchCustomer;

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
    public $productsDetails = [];

    /**
     * Customers details information.
     *
     * @var array
     */
    public $customersDetails = [];

    /**
     * Products id.
     *
     * @var array
     */
    public $productsIds = [];

    /**
     * Customers Id.
     *
     * @var array
     */
    public $customersIds = [];

    /**
     * Selected products.
     *
     * @var array
     */
    public $selectedProducts = [];

    /**
     * Selected customers that can apply the discount.
     *
     * @var array
     */
    public $selectedCustomers = [];

    /**
     * Generate Discount code.
     */
    public function generate()
    {
        $this->code = substr(strtoupper(uniqid(str_random(10))), 0, 10);
    }
}
