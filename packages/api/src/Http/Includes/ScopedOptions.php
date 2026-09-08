<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Includes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Spatie\MediaLibrary\HasMedia;
use Spatie\QueryBuilder\Includes\IncludeInterface;

final class ScopedOptions implements IncludeInterface
{
    public function __invoke(Builder $query, string $include): void
    {
        $query->with([$include => function (Relation $options): void {
            $options->getQuery()
                ->with('values')
                ->afterQuery(fn (Collection $rows): Collection => $this->scopeToUsedValues($rows));
        }]);
    }

    /**
     * @param  Collection<int, Attribute>  $rows
     * @return Collection<int, Attribute>
     */
    private function scopeToUsedValues(Collection $rows): Collection
    {
        $swatches = $this->swatchUrls($rows);
        $kept = [];

        foreach ($rows->groupBy(fn (Attribute $row): int => $this->pivotProductId($row)) as $productId => $productRows) {
            $usedValueIds = $productRows->pluck('pivot.attribute_value_id')->filter()->unique();

            foreach ($productRows->unique('id') as $option) {
                $option->setRelation('values', $option->values
                    ->whereIn('id', $usedValueIds)
                    ->each(function (AttributeValue $value) use ($swatches, $productId): void {
                        $value->swatch_url = $swatches[$productId][$value->id] ?? null;
                    })
                    ->values());

                $kept[spl_object_id($option)] = true;
            }
        }

        return $rows->filter(fn (Attribute $row): bool => isset($kept[spl_object_id($row)]))->values();
    }

    private function pivotProductId(Attribute $row): int
    {
        return (int) $row->getRelation('pivot')->getAttribute('product_id');
    }

    /**
     * @param  Collection<int, Attribute>  $rows
     * @return array<int, array<int, string>>
     */
    private function swatchUrls(Collection $rows): array
    {
        /** @var class-string<Model> $model */
        $model = config('shopper.models.attribute_product');

        return $model::query()
            ->with('media')
            ->whereIn('product_id', $rows->map(fn (Attribute $row): int => $this->pivotProductId($row))->unique())
            ->whereNotNull('attribute_value_id')
            ->get()
            ->groupBy('product_id')
            ->map(fn (Collection $links): array => $links
                ->mapWithKeys(fn (Model $link): array => [
                    (int) $link->getAttribute('attribute_value_id') => $link instanceof HasMedia
                        ? ($link->getMedia('swatch')->first()?->getFullUrl() ?? '')
                        : '',
                ])
                ->filter()
                ->all())
            ->all();
    }
}
