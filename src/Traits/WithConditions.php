<?php

declare(strict_types=1);

namespace Shopper\Framework\Traits;

trait WithConditions
{
    public array $rule = [];

    public array $operator = [];

    public array $value = [];

    public array $conditions = [];

    public int $i = 1;

    public function add(int $i): void
    {
        $i++;
        $this->i = $i;
        $this->conditions[] = $i;
    }

    public function remove(int $i): void
    {
        unset($this->conditions[$i]);
    }

    public function getCollectionRulesProperty(): array
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

    public function getOperatorsProperty(): array
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

    private function resetConditionsFields(): void
    {
        $this->reset('rule', 'operator', 'value');
    }
}
