<?php

namespace Shopper\Framework\Traits;

trait WithConditions
{
    /**
     * Condition rule.
     */
    public $rule;

    /**
     * Condition operator.
     */
    public $operator;

    /**
     * Condition value.
     */
    public $value;

    /**
     * Get the lists of all collection conditions rules.
     *
     * @var array
     */
    public $conditions = [];

    /**
     * @var int
     */
    public $i = 1;

    /**
     * Add new condition on the array conditions.
     *
     * @param  $i
     */
    public function add($i)
    {
        $i++;
        $this->i = $i;
        array_push($this->conditions, $i);
    }

    /**
     * Remove a condition to the array conditions.
     *
     * @param  $i
     */
    public function remove($i)
    {
        unset($this->conditions[$i]);
    }

    /**
     * Computed Collection Rules.
     *
     * @return array
     */
    public function getCollectionRulesProperty()
    {
        return [
            'product_title' => [
                'type' => 'string',
                'name' => __('Product title'),
            ],
            'product_brand' => [
                'type' => 'string',
                'name' => __('Product brand'),
            ],
            'product_category' => [
                'type' => 'string',
                'name' => __('Product category'),
            ],
            'product_price' => [
                'type' => 'price',
                'name' => __('Product price'),
            ],
            'compare_at_price' => [
                'type' => 'price',
                'name' => __('Compare at price'),
            ],
            'inventory_stock' => [
                'type' => 'number',
                'name' => __('Inventory stock'),
            ],
        ];
    }

    /**
     * Get all available operators.
     *
     * @return array
     */
    public function getOperatorsProperty()
    {
        return [
            'equals_to' => [
                'apply_to' => ['string', 'number', 'price'],
                'name' => __('Equals to'),
            ],
            'not_equals_to' => [
                'apply_to' => ['string', 'number', 'price'],
                'name' => __('Not equals to'),
            ],
            'less_than' => [
                'apply_to' => ['number', 'price'],
                'name' => __('Less than'),
            ],
            'greater_than' => [
                'apply_to' => ['number', 'price'],
                'name' => __('Greater than'),
            ],
            'starts_with' => [
                'apply_to' => ['string'],
                'name' => __('Starts with'),
            ],
            'ends_with' => [
                'apply_to' => ['string'],
                'name' => __('End with'),
            ],
            'contains' => [
                'apply_to' => ['string'],
                'name' => __('Contains'),
            ],
            'not_contains' => [
                'apply_to' => ['string'],
                'name' => __('Not contains'),
            ],
        ];
    }

    /**
     * Reset conditions form.
     */
    private function resetConditionsFields()
    {
        $this->rule = '';
        $this->operator = '';
        $this->value = '';
    }
}
