<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
use Shopper\Core\Models\ProductTag;

final class ProductRowImporter
{
    /**
     * @return Model&ProductContract
     */
    public function import(ProductRow $row): ProductContract
    {
        if ($row->name === '') {
            throw new ProductImportException("Product [{$row->handle}] has no name.");
        }

        /** @var Model&ProductContract $product */
        $product = DB::transaction(function () use ($row): ProductContract {
            $product = $this->upsertProduct($row);

            $this->syncCategories($product, $row->categories);
            $this->syncTags($product, $row->tags);

            if ($row->isStandard()) {
                $this->applyStandardData($product, $row->variants[0] ?? null);
            } else {
                foreach ($row->variants as $variant) {
                    $this->upsertVariant($product, $variant, $row);
                }
            }

            return $product;
        });

        $this->dispatchImageDownloads($product, $row);

        return $product;
    }

    /**
     * @return Model&ProductContract
     */
    private function upsertProduct(ProductRow $row): ProductContract
    {
        /** @var (Model&ProductContract)|null $product */
        $product = resolve(ProductContract::class)::query()->where('slug', $row->handle)->first();

        $data = [
            'name' => $row->name,
            'description' => $row->description,
            'type' => $row->isStandard() ? ProductType::Standard : ProductType::Variant,
            'is_visible' => $row->published,
            'brand_id' => $this->resolveBrand($row->brand),
            'seo_title' => $row->seoTitle,
            'seo_description' => $row->seoDescription,
            'published_at' => $product->published_at ?? now(),
        ];

        if ($product === null) {
            /** @var Model&ProductContract $product */
            $product = resolve(ProductContract::class)::query()->create([...$data, 'slug' => $row->handle]);

            event(new ProductCreated($product));

            return $product;
        }

        $product->update($data);

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
    private function applyStandardData(ProductContract $product, ?VariantRow $variant): void
    {
        if ($variant === null) {
            return;
        }

        $product->update([
            'sku' => $variant->sku,
            'barcode' => $variant->barcode,
            'allow_backorder' => $variant->allowBackorder,
            ...$this->weightData($variant),
        ]);

        $this->applyPricing($product, $variant);
        $this->applyStock($product, $variant->quantity);
    }

    /**
     * @param  Model&ProductContract  $product
     */
    private function upsertVariant(ProductContract $product, VariantRow $row, ProductRow $productRow): void
    {
        $name = $row->name($productRow->name);

        /** @var (Model&ProductVariantContract)|null $variant */
        $variant = $product->variants()
            ->when($row->sku !== null, fn ($query) => $query->where('sku', $row->sku))
            ->when($row->sku === null, fn ($query) => $query->where('name', $name))
            ->first();

        $data = [
            'name' => $name,
            'sku' => $row->sku,
            'barcode' => $row->barcode,
            'ean' => $row->ean,
            'upc' => $row->upc,
            'allow_backorder' => $row->allowBackorder,
            ...$this->weightData($row),
        ];

        if ($variant === null) {
            /** @var Model&ProductVariantContract $variant */
            $variant = $product->variants()->create([
                ...$data,
                'position' => (int) $product->variants()->max('position') + 1,
            ]);
        } else {
            $variant->update($data);
        }

        $variant->values()->sync($this->resolveOptionValues($row->options));

        $this->applyPricing($variant, $row);
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
    private function applyPricing(ProductContract|ProductVariantContract $model, VariantRow $row): void
    {
        if ($row->price === null) {
            return;
        }

        /** @var int $currencyId */
        $currencyId = shopper_setting('default_currency_id');

        $model->prices()->updateOrCreate(
            ['currency_id' => $currencyId],
            [
                'amount' => $this->toCents($row->price),
                'compare_amount' => $this->toCents($row->compareAtPrice),
                'cost_amount' => $this->toCents($row->costPerItem),
            ]
        );
    }

    /**
     * @param  (Model&ProductContract)|(Model&ProductVariantContract)  $model
     */
    private function applyStock(ProductContract|ProductVariantContract $model, int $quantity): void
    {
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
     * @return array<string, mixed>
     */
    private function weightData(VariantRow $row): array
    {
        if ($row->weightValue === null) {
            return [];
        }

        return [
            'weight_value' => $row->weightValue,
            'weight_unit' => Weight::tryFrom(mb_strtolower((string) $row->weightUnit)) ?? Weight::G,
        ];
    }

    private function toCents(?float $amount): ?int
    {
        return $amount === null ? null : (int) round($amount * 100);
    }

    /**
     * @param  Model&ProductContract  $product
     */
    private function dispatchImageDownloads(ProductContract $product, ProductRow $row): void
    {
        foreach ($row->images as $image) {
            DownloadProductImageJob::dispatch($product, $image->url, $image->alt);
        }

        if ($row->isStandard()) {
            return;
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
                DownloadProductImageJob::dispatch($variant, $variantRow->imageUrl);
            }
        }
    }
}
