<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Shopper\Core\Enum\Dimension\Weight;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Exceptions\ProductImportException;
use Shopper\Core\Import\ProductRow;
use Shopper\Core\Import\ProductRowImporter;
use Shopper\Core\Import\Sources\CsvSource;
use Shopper\Core\Import\VariantRow;
use Shopper\Core\Jobs\DownloadProductImageJob;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    Queue::fake();
    setupCurrencies();

    Inventory::factory()->create(['is_default' => true]);

    $this->importer = resolve(ProductRowImporter::class);
    $this->rows = (new CsvSource)->read(__DIR__.'/fixtures/products.csv')->all();
});

describe(ProductRowImporter::class, function (): void {
    it('imports a product with its full variant matrix', function (): void {
        $this->importer->import($this->rows[0]);

        $product = Product::findBySlug('graphic-t-shirt');

        expect($product->type)->toBe(ProductType::Variant)
            ->and($product->brand->name)->toBe('Acme Apparel')
            ->and($product->is_visible)->toBeTrue()
            ->and($product->isPublished())->toBeTrue()
            ->and($product->variants)->toHaveCount(12)
            ->and($product->tags)->toHaveCount(6);

        $variant = $product->variants()->where('sku', 'TSHIRT-S-GREEN')->firstOrFail();
        $price = $variant->prices()->firstOrFail();

        expect($variant->name)->toBe('Small / green')
            ->and($price->amount)->toBe(1999)
            ->and($price->compare_amount)->toBe(2499)
            ->and($price->cost_amount)->toBe(1100)
            ->and($variant->stock)->toBe(47)
            ->and($variant->weight_unit)->toBe(Weight::G)
            ->and((float) $variant->weight_value)->toBe(150.0)
            ->and($variant->values->pluck('value')->all())->toBe(['Small', 'green']);

        expect(Attribute::query()->where('name', 'Size')->exists())->toBeTrue()
            ->and(Attribute::query()->where('name', 'Color')->firstOrFail()->values()->count())->toBe(3);
    });

    it('creates the category tree from the category path', function (): void {
        $this->importer->import($this->rows[0]);

        $product = Product::findBySlug('graphic-t-shirt');
        $leaf = Category::query()->where('name', 'T-Shirts')->firstOrFail();

        expect($product->categories->pluck('id')->all())->toBe([$leaf->id])
            ->and($leaf->parent->name)->toBe('Clothing Tops')
            ->and($leaf->parent->parent->name)->toBe('Clothing')
            ->and($leaf->parent->parent->parent->name)->toBe('Apparel & Accessories');
    });

    it('imports a single-variant product as a standard product', function (): void {
        $this->importer->import($this->rows[2]);

        $product = Product::findBySlug('classic-perfume');
        $price = $product->prices()->firstOrFail();

        expect($product->type)->toBe(ProductType::Standard)
            ->and($product->sku)->toBe('PERFUME-CLASSIC')
            ->and($product->variants)->toHaveCount(0)
            ->and($product->is_visible)->toBeFalse()
            ->and($price->amount)->toBe(7499)
            ->and($price->compare_amount)->toBe(8000)
            ->and($product->stock)->toBe(0);
    });

    it('is idempotent when the same file is imported twice', function (): void {
        $this->importer->import($this->rows[0]);
        $this->importer->import($this->rows[0]);

        $product = Product::findBySlug('graphic-t-shirt');
        $variant = $product->variants()->where('sku', 'TSHIRT-S-GREEN')->firstOrFail();

        expect(Product::query()->count())->toBe(1)
            ->and($product->variants)->toHaveCount(12)
            ->and(Brand::query()->where('name', 'Acme Apparel')->count())->toBe(1)
            ->and(Category::query()->where('name', 'T-Shirts')->count())->toBe(1)
            ->and($variant->prices()->count())->toBe(1)
            ->and($variant->stock)->toBe(47)
            ->and($variant->values)->toHaveCount(2);
    });

    it('dispatches a download job per product image', function (): void {
        $this->importer->import($this->rows[0]);

        Queue::assertPushed(DownloadProductImageJob::class, 1);
    });

    it('rejects a product without a name', function (): void {
        $this->importer->import(new ProductRow(
            handle: 'broken-product',
            name: '',
        ));
    })->throws(ProductImportException::class);

    it('stores the price on the currency given in the file', function (): void {
        setupCurrencies(['USD', 'EUR']);

        $this->importer->import(new ProductRow(
            handle: 'euro-lamp',
            name: 'Euro Lamp',
            variants: [new VariantRow(price: 49.99, currency: 'eur')],
        ));

        $price = Product::findBySlug('euro-lamp')->prices()->firstOrFail();
        $euro = Currency::query()->where('code', 'EUR')->firstOrFail();

        expect($price->currency_id)->toBe($euro->id)
            ->and($price->amount)->toBe(4999);
    });

    it('falls back to the default store currency when the file has none', function (): void {
        $this->importer->import(new ProductRow(
            handle: 'plain-lamp',
            name: 'Plain Lamp',
            variants: [new VariantRow(price: 19.99)],
        ));

        $price = Product::findBySlug('plain-lamp')->prices()->firstOrFail();

        expect($price->currency_id)->toBe((int) shopper_setting('default_currency_id'));
    });

    it('rejects a price whose currency is not enabled on the store', function (): void {
        $this->importer->import(new ProductRow(
            handle: 'yen-lamp',
            name: 'Yen Lamp',
            variants: [new VariantRow(price: 4900, currency: 'JPY')],
        ));
    })->throws(ProductImportException::class, 'Currency [JPY] is not enabled on this store.');
});
