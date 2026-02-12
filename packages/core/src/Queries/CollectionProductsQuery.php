<?php

declare(strict_types=1);

namespace Shopper\Core\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Shopper\Core\Enum\Operator;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\Rule;
use Shopper\Core\Models\CollectionRule;
use Shopper\Core\Models\Contracts\Collection;
use Shopper\Core\Models\Contracts\Product;

final class CollectionProductsQuery
{
    /**
     * @return EloquentCollection<int, Product>
     */
    public function get(Collection $collection): EloquentCollection
    {
        if ($collection->isManual()) {
            // @phpstan-ignore-next-line
            return $collection->products()->publish()->get();
        }

        return $this->evaluateRules($collection);
    }

    /**
     * @return Builder<Product>
     */
    public function query(Collection $collection): Builder
    {
        if ($collection->isManual()) {
            // @phpstan-ignore-next-line
            return $collection->products()->publish()->getQuery();
        }

        return $this->buildRulesQuery($collection);
    }

    /**
     * Check if a product matches the collection's rules.
     */
    public function matches(Collection $collection, Product $product): bool
    {
        if ($collection->isManual() || $collection->rules->isEmpty()) {
            return false;
        }

        return $this->buildRulesQuery($collection)
            ->where($product->getQualifiedKeyName(), $product->getKey())
            ->exists();
    }

    /**
     * @return EloquentCollection<int, Product>
     */
    private function evaluateRules(Collection $collection): EloquentCollection
    {
        return $this->buildRulesQuery($collection)->get();
    }

    /**
     * @return Builder<Product>
     */
    private function buildRulesQuery(Collection $collection): Builder
    {
        /** @var Builder<Product> $query */
        $query = resolve(Product::class)::query()->publish();

        if ($collection->rules->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        $matchAll = $collection->match_conditions === 'all';

        if ($matchAll) {
            foreach ($collection->rules as $rule) {
                $this->applyRule($query, $rule);
            }
        } else {
            $query->where(function (Builder $query) use ($collection): void {
                foreach ($collection->rules as $index => $rule) {
                    if ($index === 0) {
                        $this->applyRule($query, $rule);
                    } else {
                        $query->orWhere(fn (Builder $q) => $this->applyRule($q, $rule));
                    }
                }
            });
        }

        return $query;
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applyRule(Builder $query, CollectionRule $rule): void
    {
        match ($rule->rule) {
            Rule::ProductTitle => $this->applyStringRule($query, 'name', $rule),
            Rule::ProductPrice => $this->applyNumericRule($query, 'price_amount', $rule),
            Rule::CompareAtPrice => $this->applyNumericRule($query, 'old_price_amount', $rule),
            Rule::InventoryStock => $this->applyStockRule($query, $rule),
            Rule::ProductBrand => $this->applyBrandRule($query, $rule),
            Rule::ProductCategory => $this->applyCategoryRule($query, $rule),
            Rule::ProductCreatedAt => $this->applyDateRule($query, 'created_at', $rule),
            Rule::ProductFeatured => $query->where('featured', (bool) $rule->value),
            Rule::ProductRating => $this->applyRatingRule($query, $rule),
            Rule::ProductSalesCount => $this->applySalesCountRule($query, $rule),
        };
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applyStringRule(Builder $query, string $column, CollectionRule $rule): void
    {
        match ($rule->operator) {
            Operator::EqualsTo => $query->where($column, $rule->value),
            Operator::NotEqualTo => $query->where($column, '!=', $rule->value),
            Operator::Contains => $query->where($column, 'like', "%{$rule->value}%"),
            Operator::NotContains => $query->where($column, 'not like', "%{$rule->value}%"),
            Operator::StartsWith => $query->where($column, 'like', "{$rule->value}%"),
            Operator::EndsWith => $query->where($column, 'like', "%{$rule->value}"),
            default => null,
        };
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applyNumericRule(Builder $query, string $column, CollectionRule $rule): void
    {
        $operator = match ($rule->operator) {
            Operator::NotEqualTo => '!=',
            Operator::GreaterThan => '>',
            Operator::LessThan => '<',
            default => '=',
        };

        $query->where($column, $operator, (int) $rule->value);
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applyStockRule(Builder $query, CollectionRule $rule): void
    {
        $operator = match ($rule->operator) {
            Operator::NotEqualTo => '!=',
            Operator::GreaterThan => '>',
            Operator::LessThan => '<',
            default => '=',
        };

        /** @var class-string<Product> $productModel */
        $productModel = config('shopper.models.product');

        $query->whereHas('inventoryHistories', function (Builder $subQuery) use ($rule, $operator, $productModel): void {
            $subQuery->select('stockable_id')
                ->where('stockable_type', (new $productModel)->getMorphClass())
                ->groupBy('stockable_id')
                ->havingRaw('SUM(quantity) '.$operator.' ?', [(int) $rule->value]);
        });
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applyBrandRule(Builder $query, CollectionRule $rule): void
    {
        $query->whereHas('brand', function (Builder $q) use ($rule): void {
            $this->applyStringRule($q, 'name', $rule); // @phpstan-ignore-line
        });
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applyCategoryRule(Builder $query, CollectionRule $rule): void
    {
        $method = in_array($rule->operator, [Operator::NotEqualTo, Operator::NotContains], true)
            ? 'whereDoesntHave'
            : 'whereHas';

        $query->{$method}('categories', function (Builder $q) use ($rule): void {
            $this->applyStringRule($q, 'name', $rule);
        });
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applyDateRule(Builder $query, string $column, CollectionRule $rule): void
    {
        $operator = match ($rule->operator) {
            Operator::GreaterThan => '>',
            Operator::LessThan => '<',
            default => '=',
        };

        $query->whereDate($column, $operator, $rule->value);
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applyRatingRule(Builder $query, CollectionRule $rule): void
    {
        $operator = match ($rule->operator) {
            Operator::NotEqualTo => '!=',
            Operator::GreaterThan => '>',
            Operator::LessThan => '<',
            default => '=',
        };

        $query->whereHas('ratings', function (Builder $subQuery) use ($operator, $rule): void { // @phpstan-ignore-line
            $subQuery->select('reviewrateable_id')
                ->where('approved', true)
                ->groupBy('reviewrateable_id')
                ->havingRaw('AVG(rating) '.$operator.' ?', [(float) $rule->value]);
        });
    }

    /**
     * @param  Builder<Product>  $query
     */
    private function applySalesCountRule(Builder $query, CollectionRule $rule): void
    {
        $operator = match ($rule->operator) {
            Operator::NotEqualTo => '!=',
            Operator::GreaterThan => '>',
            Operator::LessThan => '<',
            default => '=',
        };

        /** @var class-string<Product> $productModel */
        $productModel = config('shopper.models.product');

        $validStatuses = [
            OrderStatus::Paid->value,
            OrderStatus::Shipped->value,
            OrderStatus::Delivered->value,
            OrderStatus::Completed->value,
        ];

        $query->whereIn(
            $query->getModel()->getQualifiedKeyName(),
            fn (\Illuminate\Database\Query\Builder $subQuery) => $subQuery // @phpstan-ignore-line
                ->select('product_id')
                ->from(shopper_table('order_items'))
                ->join(
                    shopper_table('orders'),
                    shopper_table('orders').'.id',
                    '=',
                    shopper_table('order_items').'.order_id'
                )
                ->where('product_type', (new $productModel)->getMorphClass())
                ->whereIn(shopper_table('orders').'.status', $validStatuses)
                ->groupBy('product_id')
                ->havingRaw('SUM(quantity) '.$operator.' ?', [(int) $rule->value])
        );
    }
}
