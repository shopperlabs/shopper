<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Shopper\Core\Enum\Dimension\Weight;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Events\Products\ProductCreated;
use Shopper\Core\Exceptions\ProductImportException;
use Shopper\Core\Jobs\DownloadProductImageJob;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Contracts\Brand as BrandContract;
use Shopper\Core\Models\Contracts\Category as CategoryContract;
use Shopper\Core\Models\Contracts\Inventory as InventoryContract;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Contracts\ProductVariant as ProductVariantContract;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\ProductTag;

final class ProductRowImporter
{
    private const array FIELDS = [
        'brand_id' => 'brand',
        'is_visible' => 'published',
        'published_at' => 'published',
        'weight_unit' => 'weight_value',
        'amount' => 'price',
        'compare_amount' => 'compare_at_price',
        'cost_amount' => 'cost_per_item',
    ];

    /** @var array<string, int> */
    private array $currencyIds = [];

    /**
     * @return Model&ProductContract
     */
    public function import(ProductRow $row): ProductContract
    {
        if ($row->name === '') {
            throw new ProductImportException("Product [{$row->handle}] has no name.");
        }

        /** @var Model&ProductContract $product */
        $product = resolve(ProductContract::class)::withoutSyncingToSearch(fn (): ProductContract => DB::transaction(function () use ($row): ProductContract {
            $product = $this->upsertProduct($row);

            $this->syncCategories($product, $row->categories);
            $this->syncTags($product, $row->tags);

            if ($product->type === ProductType::Variant) {
                foreach ($row->variants as $variant) {
                    $this->upsertVariant($product, $variant, $row);
                }
            } else {
                $this->applyStandardData($product, $row, $row->variants[0] ?? null);
            }

            return $product;
        }));

        resolve(ProductContract::class)::query()->whereKey($product->getKey())->searchable();

        return $product;
    }

    /**
     * @param  Model&ProductContract  $product
     * @return array<int, DownloadProductImageJob>
     */
    public function imageDownloads(ProductContract $product, ProductRow $row): array
    {
        $jobs = collect($row->images)
            ->unique('url')
            ->map(fn (ImageRow $image): DownloadProductImageJob => new DownloadProductImageJob($product, $image->url, $image->alt, $image->position))
            ->values()
            ->all();

        if ($product->type !== ProductType::Variant) {
            return $jobs;
        }

        foreach ($row->variants as $variantRow) {
            if ($variantRow->imageUrl === null) {
                continue;
            }

            /** @var (Model&ProductVariantContract)|null $variant */
            $variant = $product->variants()
                ->when($variantRow->sku !== null, fn ($query) => $query->where('sku', $variantRow->sku))
                ->when($variantRow->sku === null, fn ($query) => $query->where('name', $variantRow->name($row->name)))
                ->first();

            if ($variant !== null) {
                $jobs[] = new DownloadProductImageJob($variant, $variantRow->imageUrl);
            }
        }

        return $jobs;
    }

    /**
     * @return Model&ProductContract
     */
    private function upsertProduct(ProductRow $row): ProductContract
    {
        $slug = Str::slug($row->handle);

        if ($slug === '') {
            throw new ProductImportException("Product [{$row->handle}] has a handle that cannot be used in a URL.");
        }

        /** @var (Model&ProductContract)|null $product */
        $product = resolve(ProductContract::class)::query()->withTrashed()->where('slug', $slug)->first();

        $type = $row->isStandard() ? ProductType::Standard : ProductType::Variant;

        $data = [
            'name' => $row->name,
            'description' => $row->description,
            'is_visible' => $row->published ?? $product->is_visible ?? true,
            'brand_id' => $this->resolveBrand($row->brand),
            'seo_title' => $row->seoTitle,
            'seo_description' => $row->seoDescription,
            'published_at' => $product->published_at ?? now(),
        ];

        if ($product === null) {
            /** @var Model&ProductContract $product */
            $product = resolve(ProductContract::class)::query()->create([...$data, 'type' => $type, 'slug' => $slug]);

            event(new ProductCreated($product));

            return $product;
        }

        if ($product->trashed()) {
            $product->restore();
        }

        $attributes = $this->provided($row, $data);

        if ($product->type === ProductType::Standard) {
            $attributes['type'] = $type;
        }

        $product->update($attributes);

        return $product;
    }

    private function resolveBrand(?string $name): ?int
    {
        if ($name === null) {
            return null;
        }

        $brand = resolve(BrandContract::class)::query()->where('name', $name)->first()
            ?? resolve(BrandContract::class)::query()->create([
                'name' => $name,
                'slug' => $name,
                'is_enabled' => true,
            ]);

        return $brand->id;
    }

    /**
     * @param  Model&ProductContract  $product
     * @param  array<int, string>  $segments
     */
    private function syncCategories(ProductContract $product, array $segments): void
    {
        $parentId = null;
        $category = null;

        foreach ($segments as $segment) {
            $category = resolve(CategoryContract::class)::query()
                ->where('name', $segment)
                ->where('parent_id', $parentId)
                ->first()
                ?? resolve(CategoryContract::class)::query()->create([
                    'name' => $segment,
                    'slug' => $segment,
                    'parent_id' => $parentId,
                    'is_enabled' => true,
                ]);

            $parentId = $category->id;
        }

        if ($category !== null) {
            $product->categories()->syncWithoutDetaching([$category->id]);
        }
    }

    /**
     * @param  Model&ProductContract  $product
     * @param  array<int, string>  $tags
     */
    private function syncTags(ProductContract $product, array $tags): void
    {
        $ids = [];

        foreach ($tags as $tag) {
            $ids[] = (ProductTag::query()->where('name', $tag)->first()
                ?? ProductTag::query()->create(['name' => $tag, 'slug' => $tag]))->id;
        }

        if ($ids !== []) {
            $product->tags()->syncWithoutDetaching($ids);
        }
    }

    /**
     * @param  Model&ProductContract  $product
     */
    private function applyStandardData(ProductContract $product, ProductRow $row, ?VariantRow $variant): void
    {
        if ($variant === null) {
            return;
        }

        $attributes = $this->provided($row, [
            'sku' => $variant->sku,
            'barcode' => $variant->barcode,
            'allow_backorder' => $variant->allowBackorder,
            ...$this->weightData($row, $variant),
        ]);

        $this->ensureCodesAreFree(
            $row,
            resolve(ProductContract::class)::query()->withTrashed(),
            Arr::only($attributes, ['sku', 'barcode']),
            $product->getKey(),
        );

        $product->update($attributes);

        $this->applyPricing($product, $row, $variant);
        $this->applyStock($product, $variant->quantity);
    }

    /**
     * @param  Model&ProductContract  $product
     */
    private function upsertVariant(ProductContract $product, VariantRow $row, ProductRow $productRow): void
    {
        $name = $row->name($productRow->name);
        $options = $productRow->optionNames !== [];

        if ($row->sku === null && ! $options) {
            $values = [$row->barcode, $row->ean, $row->upc, $row->price, $row->quantity, $row->weightValue, $row->imageUrl];

            if (array_filter($values, fn (mixed $value): bool => $value !== null) === []) {
                return;
            }

            throw new ProductImportException("Product [{$productRow->handle}] has a variant without SKU or options.");
        }

        /** @var (Model&ProductVariantContract)|null $variant */
        $variant = $product->variants()
            ->when($row->sku !== null, fn ($query) => $query->where('sku', $row->sku))
            ->when($row->sku === null, fn ($query) => $query->where('name', $name))
            ->first();

        $data = $this->provided($productRow, [
            'sku' => $row->sku,
            'barcode' => $row->barcode,
            'ean' => $row->ean,
            'upc' => $row->upc,
            'allow_backorder' => $row->allowBackorder,
            ...$this->weightData($productRow, $row),
        ]);

        $this->ensureCodesAreFree(
            $productRow,
            resolve(ProductVariantContract::class)::query()->with(['product' => fn ($query) => $query->withTrashed()]),
            Arr::only($data, ['sku', 'barcode', 'ean', 'upc']),
            $variant?->getKey(),
        );

        if ($variant === null) {
            /** @var Model&ProductVariantContract $variant */
            $variant = $product->variants()->create([
                ...$data,
                'name' => $name,
                'position' => (int) $product->variants()->max('position') + 1,
            ]);
        } else {
            $variant->update($options ? [...$data, 'name' => $name] : $data);
        }

        if ($options) {
            $variant->values()->sync($this->resolveOptionValues($row->options));
        }

        $this->applyPricing($variant, $productRow, $row);
        $this->applyStock($variant, $row->quantity);
    }

    /**
     * @param  array<string, string>  $options
     * @return array<int, int>
     */
    private function resolveOptionValues(array $options): array
    {
        $ids = [];

        foreach ($options as $name => $value) {
            $attribute = Attribute::query()->where('name', $name)->first()
                ?? Attribute::query()->create([
                    'name' => $name,
                    'slug' => $name,
                    'type' => FieldType::Select,
                    'is_enabled' => true,
                ]);

            $ids[] = AttributeValue::query()->firstOrCreate(
                ['attribute_id' => $attribute->id, 'value' => $value],
                [
                    'key' => str($value)->slug()->toString(),
                    'position' => (int) $attribute->values()->max('position') + 1,
                ]
            )->id;
        }

        return $ids;
    }

    /**
     * @param  (Model&ProductContract)|(Model&ProductVariantContract)  $model
     */
    private function applyPricing(ProductContract|ProductVariantContract $model, ProductRow $productRow, VariantRow $row): void
    {
        if (min($row->price ?? 0, $row->compareAtPrice ?? 0, $row->costPerItem ?? 0) < 0) {
            throw new ProductImportException("Product [{$productRow->handle}] has a negative price.");
        }

        $currency = $row->currency === null ? shopper_currency() : mb_strtoupper($row->currency);

        $amounts = $this->provided($productRow, [
            'amount' => $this->toMinorUnits($row->price, $currency),
            'compare_amount' => $this->toMinorUnits($row->compareAtPrice, $currency),
            'cost_amount' => $this->toMinorUnits($row->costPerItem, $currency),
        ]);

        if ($row->price !== null) {
            $model->prices()->updateOrCreate(['currency_id' => $this->resolveCurrencyId($row->currency)], $amounts);

            return;
        }

        $amounts = Arr::except($amounts, 'amount');

        if ($amounts !== []) {
            $model->prices()->where('currency_id', $this->resolveCurrencyId($row->currency))->first()?->update($amounts);
        }
    }

    /**
     * @param  (Model&ProductContract)|(Model&ProductVariantContract)  $model
     */
    private function applyStock(ProductContract|ProductVariantContract $model, ?int $quantity): void
    {
        if ($quantity === null) {
            return;
        }

        /** @var (Model&InventoryContract)|null $inventory */
        $inventory = resolve(InventoryContract::class)::query()->scopes('default')->first();

        if ($inventory === null) {
            return;
        }

        $model->setStock(
            newQuantity: $quantity,
            inventoryId: $inventory->id,
            event: __('shopper-core::import.inventory_event'),
        );
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function provided(ProductRow $row, array $attributes): array
    {
        return array_filter(
            $attributes,
            fn (string $attribute): bool => $row->provides(self::FIELDS[$attribute] ?? $attribute),
            ARRAY_FILTER_USE_KEY,
        );
    }

    /**
     * @param  Builder<Model>  $query
     * @param  array<string, ?string>  $codes
     */
    private function ensureCodesAreFree(ProductRow $row, Builder $query, array $codes, mixed $ignore): void
    {
        foreach (array_filter($codes) as $column => $code) {
            $owner = $query->clone()
                ->where($column, $code)
                ->when($ignore !== null, fn (Builder $query) => $query->whereKeyNot($ignore))
                ->first();

            if ($owner === null) {
                continue;
            }

            /** @var Model&ProductContract $product */
            $product = $owner instanceof ProductVariantContract ? $owner->product : $owner;

            throw new ProductImportException(sprintf(
                'Product [%s] uses the %s [%s] of product [%s]%s.',
                $row->handle,
                $column,
                $code,
                $product->slug,
                $product->trashed() ? ', which is deleted' : '',
            ));
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function weightData(ProductRow $productRow, VariantRow $row): array
    {
        if ($row->weightValue === null) {
            return [];
        }

        $unit = $row->weightUnit === null
            ? Weight::G
            : Weight::tryFrom(mb_strtolower($row->weightUnit)) ?? throw new ProductImportException("Product [{$productRow->handle}] has an unknown weight unit [{$row->weightUnit}].");

        return [
            'weight_value' => $row->weightValue,
            'weight_unit' => $unit,
        ];
    }

    private function resolveCurrencyId(?string $code): int
    {
        if ($code === null) {
            return (int) shopper_setting('default_currency_id');
        }

        $code = mb_strtoupper($code);

        if (isset($this->currencyIds[$code])) {
            return $this->currencyIds[$code];
        }

        /** @var Currency|null $currency */
        $currency = Currency::query()
            ->whereIn('id', shopper_setting('currencies') ?? [])
            ->where('code', $code)
            ->first();

        if ($currency === null) {
            throw new ProductImportException("Currency [{$code}] is not enabled on this store.");
        }

        return $this->currencyIds[$code] = $currency->id;
    }

    private function toMinorUnits(?float $amount, string $currency): ?int
    {
        if ($amount === null) {
            return null;
        }

        $decimals = is_no_division_currency($currency) ? 0 : 2;

        return (int) round(round($amount, $decimals) * 10 ** $decimals);
    }
}
