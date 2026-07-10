<?php

declare(strict_types=1);

use Shopper\Core\Exceptions\ProductImportException;
use Shopper\Core\Import\ProductRow;
use Shopper\Core\Import\Sources\CsvSource;

uses(Tests\Core\TestCase::class);

describe(CsvSource::class, function (): void {
    it('groups flat rows into one product per handle', function (): void {
        $products = (new CsvSource)->read(__DIR__.'/fixtures/products.csv')->all();

        expect($products)->toHaveCount(3)
            ->and($products[0]->handle)->toBe('graphic-t-shirt')
            ->and($products[1]->handle)->toBe('music-ebook')
            ->and($products[2]->handle)->toBe('classic-perfume');
    });

    it('rebuilds the variant matrix from the product template', function (): void {
        /** @var ProductRow $product */
        $product = (new CsvSource)->read(__DIR__.'/fixtures/products.csv')->first();

        expect($product->name)->toBe('Graphic T-Shirt')
            ->and($product->brand)->toBe('Acme Apparel')
            ->and($product->optionNames)->toBe(['Size', 'Color'])
            ->and($product->isStandard())->toBeFalse()
            ->and($product->variants)->toHaveCount(12)
            ->and($product->categories)->toBe(['Apparel & Accessories', 'Clothing', 'Clothing Tops', 'T-Shirts'])
            ->and($product->tags)->toHaveCount(6)
            ->and($product->published)->toBeTrue()
            ->and($product->images)->toHaveCount(1)
            ->and($product->images[0]->position)->toBe(1);

        $first = $product->variants[0];

        expect($first->sku)->toBe('TSHIRT-S-GREEN')
            ->and($first->options)->toBe(['Size' => 'Small', 'Color' => 'green'])
            ->and($first->name($product->name))->toBe('Small / green')
            ->and($first->price)->toBe(19.99)
            ->and($first->compareAtPrice)->toBe(24.99)
            ->and($first->costPerItem)->toBe(11.00)
            ->and($first->quantity)->toBe(47)
            ->and($first->weightValue)->toBe(150.0)
            ->and($first->weightUnit)->toBe('g');
    });

    it('treats a product without options as a standard product', function (): void {
        /** @var ProductRow $perfume */
        $perfume = (new CsvSource)->read(__DIR__.'/fixtures/products.csv')->last();

        expect($perfume->isStandard())->toBeTrue()
            ->and($perfume->optionNames)->toBe([])
            ->and($perfume->variants)->toHaveCount(1)
            ->and($perfume->variants[0]->sku)->toBe('PERFUME-CLASSIC')
            ->and($perfume->published)->toBeFalse();
    });

    it('splits multiple image urls from a single column', function (): void {
        $path = tempnam(sys_get_temp_dir(), 'import');
        file_put_contents($path, implode("\n", [
            'handle,name,image_url',
            'mug,Mug,https://example.com/a.jpg; https://example.com/b.jpg;https://example.com/c.jpg',
        ]));

        $product = (new CsvSource)->read($path)->first();

        expect($product->images)->toHaveCount(3)
            ->and($product->images[1]->url)->toBe('https://example.com/b.jpg');
    });

    it('lists the file headers', function (): void {
        $headers = (new CsvSource)->headers(__DIR__.'/fixtures/products.csv');

        expect($headers)->toContain('handle', 'name', 'price', 'quantity');
    });

    it('reads a file through a custom column mapping', function (): void {
        $products = (new CsvSource)
            ->withMapping([
                'handle' => 'Reference',
                'name' => 'Product Title',
                'brand' => 'Vendor Name',
                'sku' => 'Reference',
                'price' => 'Unit Price',
                'quantity' => 'Stock',
            ])
            ->read(__DIR__.'/fixtures/custom_headers.csv')
            ->all();

        expect($products)->toHaveCount(2)
            ->and($products[0]->name)->toBe('Blue Mug')
            ->and($products[0]->brand)->toBe('Acme')
            ->and($products[0]->isStandard())->toBeTrue()
            ->and($products[0]->variants[0]->sku)->toBe('MUG-BLUE')
            ->and($products[0]->variants[0]->price)->toBe(12.50)
            ->and($products[0]->variants[0]->quantity)->toBe(8)
            ->and($products[1]->handle)->toBe('MUG-RED');
    });

    it('rejects a file without the required columns', function (): void {
        $path = tempnam(sys_get_temp_dir(), 'import');
        file_put_contents($path, "foo,bar\n1,2\n");

        (new CsvSource)->read($path)->all();
    })->throws(ProductImportException::class);
});
