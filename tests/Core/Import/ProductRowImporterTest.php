<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Shopper\Core\Enum\Dimension\Weight;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Exceptions\ProductImportException;
use Shopper\Core\Import\ImageRow;
use Shopper\Core\Import\ProductRow;
use Shopper\Core\Import\ProductRowImporter;
use Shopper\Core\Import\Sources\CsvSource;
use Shopper\Core\Import\VariantRow;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;

uses(Tests\Core\TestCase::class);

/**
 * @return array<int, ProductRow>
 */
function importRowsFromCsv(string $contents): array
{
    $path = tempnam(sys_get_temp_dir(), 'import');
    file_put_contents($path, $contents);

    return (new CsvSource)->read($path)->all();
}

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

    it('records the stock before a re-import changes it', function (): void {
        $this->importer->import(new ProductRow(handle: 'mug', name: 'Mug', variants: [new VariantRow(quantity: 5)]));
        $this->importer->import(new ProductRow(handle: 'mug', name: 'Mug', variants: [new VariantRow(quantity: 8)]));

        $movement = Product::findBySlug('mug')->inventoryHistories()->latest('id')->firstOrFail();

        expect($movement->old_quantity)->toBe(5)
            ->and($movement->stock_after)->toBe(8);
    });

    it('downloads each distinct image url once', function (): void {
        $row = new ProductRow(handle: 'tee', name: 'Tee', images: [
            new ImageRow('https://cdn.test/front.png'),
            new ImageRow('https://cdn.test/front.png', position: 2),
            new ImageRow('https://cdn.test/back.png', position: 3),
        ]);

        $jobs = $this->importer->imageDownloads($this->importer->import($row), $row);

        expect($jobs)->toHaveCount(2)
            ->and($jobs[1]->url)->toBe('https://cdn.test/back.png')
            ->and($jobs[1]->position)->toBe(3);
    });

    it('downloads each variant image on its own variant', function (): void {
        $row = new ProductRow(handle: 'tee', name: 'Tee', optionNames: ['Size'], variants: [
            new VariantRow(options: ['Size' => 'Small'], sku: 'TEE-S', imageUrl: 'https://cdn.test/small.png'),
            new VariantRow(options: ['Size' => 'Large'], imageUrl: 'https://cdn.test/large.png'),
        ]);

        $jobs = $this->importer->imageDownloads($this->importer->import($row), $row);

        expect($jobs)->toHaveCount(2)
            ->and($jobs[0]->model->sku)->toBe('TEE-S')
            ->and($jobs[0]->url)->toBe('https://cdn.test/small.png')
            ->and($jobs[1]->model->name)->toBe('Large')
            ->and($jobs[1]->url)->toBe('https://cdn.test/large.png');
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

    it('stores a zero-decimal currency price as given in the file', function (): void {
        setupCurrencies(['USD', 'JPY']);

        $this->importer->import(new ProductRow(
            handle: 'yen-lamp',
            name: 'Yen Lamp',
            variants: [new VariantRow(price: 4900, compareAtPrice: 5900, currency: 'jpy')],
        ));

        $price = Product::findBySlug('yen-lamp')->prices()->firstOrFail();

        expect($price->amount)->toBe(4900)
            ->and($price->compare_amount)->toBe(5900);
    });

    it('updates the product of a handle that is not written as a slug', function (): void {
        $this->importer->import(new ProductRow(handle: 'Mug Red', name: 'Mug'));
        $this->importer->import(new ProductRow(handle: 'Mug Red', name: 'Red mug'));

        $product = Product::query()->sole();

        expect($product->slug)->toBe('mug-red')
            ->and($product->name)->toBe('Red mug');
    });

    it('rejects a price whose currency is not enabled on the store', function (): void {
        $this->importer->import(new ProductRow(
            handle: 'yen-lamp',
            name: 'Yen Lamp',
            variants: [new VariantRow(price: 4900, currency: 'JPY')],
        ));
    })->throws(ProductImportException::class, 'Currency [JPY] is not enabled on this store.');

    it('rejects a handle that cannot be used in a URL', function (): void {
        $this->importer->import(new ProductRow(handle: '!!!', name: 'Mug'));
    })->throws(ProductImportException::class, 'Product [!!!] has a handle that cannot be used in a URL.');

    it('restores a deleted product instead of creating a second one', function (): void {
        $this->importer->import(new ProductRow(handle: 'mug', name: 'Mug'));
        Product::findBySlug('mug')->delete();

        $this->importer->import(new ProductRow(handle: 'mug', name: 'Red mug'));

        $product = Product::withTrashed()->sole();

        expect($product->trashed())->toBeFalse()
            ->and($product->name)->toBe('Red mug');
    });

    it('rejects a negative price', function (): void {
        $this->importer->import(new ProductRow(
            handle: 'mug',
            name: 'Mug',
            variants: [new VariantRow(price: 12.0, compareAtPrice: -5.0)],
        ));
    })->throws(ProductImportException::class, 'Product [mug] has a negative price.');

    it('keeps the values of the columns a re-import leaves out', function (): void {
        foreach (importRowsFromCsv("handle,name,published,price,compare_at_price,quantity\nmug,Mug,FALSE,12,15,5\n") as $row) {
            $this->importer->import($row);
        }

        foreach (importRowsFromCsv("handle,name,price\nmug,Mug,10\n") as $row) {
            $this->importer->import($row);
        }

        $product = Product::findBySlug('mug');
        $price = $product->prices()->sole();

        expect($product->is_visible)->toBeFalse()
            ->and($product->stock)->toBe(5)
            ->and($price->amount)->toBe(1000)
            ->and($price->compare_amount)->toBe(1500);
    });

    it('keeps the options of a variant product re-imported with blank option cells', function (): void {
        $this->importer->import($this->rows[0]);

        foreach (importRowsFromCsv("handle,name,sku,price,option1_name,option1_value\ngraphic-t-shirt,Graphic T-Shirt,TSHIRT-S-GREEN,21,,\n") as $row) {
            $this->importer->import($row);
        }

        $product = Product::findBySlug('graphic-t-shirt');
        $variant = $product->variants()->where('sku', 'TSHIRT-S-GREEN')->firstOrFail();

        expect($product->type)->toBe(ProductType::Variant)
            ->and($product->variants)->toHaveCount(12)
            ->and($variant->name)->toBe('Small / green')
            ->and($variant->values)->toHaveCount(2)
            ->and($variant->prices()->sole()->amount)->toBe(2100);
    });

    it('updates a variant product from a file without variant columns', function (): void {
        $this->importer->import($this->rows[0]);

        foreach (importRowsFromCsv("handle,name,description,sku\ngraphic-t-shirt,Graphic T-Shirt,New copy,\n") as $row) {
            $this->importer->import($row);
        }

        $product = Product::findBySlug('graphic-t-shirt');

        expect($product->description)->toBe('New copy')
            ->and($product->type)->toBe(ProductType::Variant)
            ->and($product->variants)->toHaveCount(12);
    });

    it('rejects a price for a variant product row without SKU or options', function (): void {
        $this->importer->import($this->rows[0]);

        foreach (importRowsFromCsv("handle,name,price\ngraphic-t-shirt,Graphic T-Shirt,25\n") as $row) {
            $this->importer->import($row);
        }
    })->throws(ProductImportException::class, 'has a variant without SKU or options');

    it('keeps the stock and the visibility when their cells are blank', function (): void {
        foreach (importRowsFromCsv("handle,name,published,quantity\nmug,Mug,FALSE,5\n") as $row) {
            $this->importer->import($row);
        }

        foreach (importRowsFromCsv("handle,name,published,quantity\nmug,Mug,,\n") as $row) {
            $this->importer->import($row);
        }

        $product = Product::findBySlug('mug');

        expect($product->is_visible)->toBeFalse()
            ->and($product->stock)->toBe(5);
    });

    it('names the deleted product that holds the SKU of a row', function (): void {
        $this->importer->import(new ProductRow(handle: 'mug', name: 'Mug', variants: [new VariantRow(sku: 'MUG-1')]));
        Product::findBySlug('mug')->delete();

        $this->importer->import(new ProductRow(handle: 'cup', name: 'Cup', variants: [new VariantRow(sku: 'MUG-1')]));
    })->throws(ProductImportException::class, 'Product [cup] uses the sku [MUG-1] of product [mug], which is deleted.');

    it('rejects a variant barcode held by another product', function (): void {
        $this->importer->import($this->rows[0]);

        $this->importer->import(new ProductRow(
            handle: 'hoodie',
            name: 'Hoodie',
            optionNames: ['Size'],
            variants: [new VariantRow(options: ['Size' => 'Small'], sku: 'HOODIE-S', barcode: '1234567890101')],
        ));
    })->throws(ProductImportException::class, 'Product [hoodie] uses the barcode [1234567890101] of product [graphic-t-shirt].');

    it('rejects an unknown weight unit', function (): void {
        $this->importer->import(new ProductRow(
            handle: 'mug',
            name: 'Mug',
            variants: [new VariantRow(weightValue: 1.5, weightUnit: 'kgs')],
        ));
    })->throws(ProductImportException::class, 'Product [mug] has an unknown weight unit [kgs].');

    it('updates the compare-at price and the cost of a row without price', function (): void {
        foreach (importRowsFromCsv("handle,name,price,compare_at_price,cost_per_item\nmug,Mug,12,15,5\n") as $row) {
            $this->importer->import($row);
        }

        foreach (importRowsFromCsv("handle,name,price,compare_at_price,cost_per_item\nmug,Mug,,18,6\n") as $row) {
            $this->importer->import($row);
        }

        $price = Product::findBySlug('mug')->prices()->sole();

        expect($price->amount)->toBe(1200)
            ->and($price->compare_amount)->toBe(1800)
            ->and($price->cost_amount)->toBe(600);
    });

    it('rounds a price half up to the cents of its currency', function (float $price, int $amount): void {
        $this->importer->import(new ProductRow(
            handle: 'mug',
            name: 'Mug',
            variants: [new VariantRow(price: $price)],
        ));

        expect(Product::findBySlug('mug')->prices()->sole()->amount)->toBe($amount);
    })->with([
        [0.285, 29],
        [1.005, 101],
        [8.3333, 833],
    ]);
});
