<?php

declare(strict_types=1);

namespace Shopper\Core\Import\Sources;

use Generator;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;
use Shopper\Core\Exceptions\ProductImportException;
use Shopper\Core\Import\Contracts\ImportSource;
use Shopper\Core\Import\Contracts\SupportsColumnMapping;
use Shopper\Core\Import\ImageRow;
use Shopper\Core\Import\ProductRow;
use Shopper\Core\Import\VariantRow;

final class CsvSource implements ImportSource, SupportsColumnMapping
{
    private const array REQUIRED_COLUMNS = ['handle', 'name'];

    /** @var array<string, string> */
    private array $mapping = [];

    public function code(): string
    {
        return 'csv';
    }

    public function name(): string
    {
        return __('shopper-core::import.sources.csv.name');
    }

    public function description(): string
    {
        return __('shopper-core::import.sources.csv.description');
    }

    public function icon(): string
    {
        return 'phosphor-file-csv-duotone';
    }

    public function isConfigured(): bool
    {
        return true;
    }

    /**
     * @return array<int, string>
     */
    public function headers(string $path): array
    {
        $reader = Reader::from($path);
        $reader->setHeaderOffset(0);

        return array_values(array_filter($reader->getHeader()));
    }

    public function withMapping(array $mapping): static
    {
        $clone = clone $this;
        $clone->mapping = array_filter($mapping);

        return $clone;
    }

    public function read(string $path): LazyCollection
    {
        return LazyCollection::make(function () use ($path): Generator {
            $reader = Reader::from($path);
            $reader->setHeaderOffset(0);

            $this->validateHeader($reader->getHeader());

            $handle = null;
            $buffer = [];

            foreach ($reader->getRecords() as $record) {
                $rowHandle = mb_trim((string) ($record[$this->column('handle')] ?? ''));

                if ($rowHandle === '') {
                    continue;
                }

                if ($handle !== null && $rowHandle !== $handle && $buffer !== []) {
                    yield $this->makeProduct($buffer);
                    $buffer = [];
                }

                $handle = $rowHandle;
                $buffer[] = $record;
            }

            if ($buffer !== []) {
                yield $this->makeProduct($buffer);
            }
        });
    }

    /**
     * @param  array<int, ?string>  $header
     */
    private function validateHeader(array $header): void
    {
        $missing = array_diff(
            array_map($this->column(...), self::REQUIRED_COLUMNS),
            $header
        );

        if ($missing !== []) {
            throw new ProductImportException(sprintf(
                'The file does not match the product import template: missing the %s column.',
                implode(', ', $missing)
            ));
        }
    }

    private function column(string $key): string
    {
        return $this->mapping[$key] ?? $key;
    }

    /**
     * @param  array<int, array<string, ?string>>  $rows
     */
    private function makeProduct(array $rows): ProductRow
    {
        $first = $rows[0];

        $optionNames = array_values(array_filter([
            $this->value($first, 'option1_name'),
            $this->value($first, 'option2_name'),
            $this->value($first, 'option3_name'),
        ]));

        $variants = [];
        $images = [];

        foreach ($rows as $index => $row) {
            if ($this->isVariantRow($row, $index)) {
                $variants[] = $this->makeVariant($row, $optionNames);
            }

            foreach ($this->imageUrls($row) as $url) {
                $images[] = new ImageRow(
                    url: $url,
                    position: (int) ($this->value($row, 'image_position') ?? count($images) + 1),
                    alt: $this->value($row, 'image_alt'),
                );
            }
        }

        return new ProductRow(
            handle: (string) $this->value($first, 'handle'),
            name: (string) $this->value($first, 'name'),
            description: $this->value($first, 'description'),
            brand: $this->value($first, 'brand'),
            categories: array_values(array_filter(array_map(
                mb_trim(...),
                explode('>', (string) $this->value($first, 'category'))
            ))),
            tags: array_values(array_filter(array_map(
                mb_trim(...),
                explode(',', (string) $this->value($first, 'tags'))
            ))),
            published: $this->boolean($first, 'published', default: true),
            seoTitle: $this->value($first, 'seo_title'),
            seoDescription: $this->value($first, 'seo_description'),
            optionNames: $optionNames,
            variants: $variants,
            images: $images,
        );
    }

    /**
     * @param  array<string, ?string>  $row
     * @param  array<int, string>  $optionNames
     */
    private function makeVariant(array $row, array $optionNames): VariantRow
    {
        $options = [];

        foreach ($optionNames as $index => $name) {
            $value = $this->value($row, 'option'.($index + 1).'_value');

            if ($value !== null) {
                $options[$name] = $value;
            }
        }

        return new VariantRow(
            options: $options,
            sku: $this->value($row, 'sku'),
            barcode: $this->value($row, 'barcode'),
            ean: $this->value($row, 'ean'),
            upc: $this->value($row, 'upc'),
            price: $this->float($row, 'price'),
            compareAtPrice: $this->float($row, 'compare_at_price'),
            costPerItem: $this->float($row, 'cost_per_item'),
            currency: $this->value($row, 'currency'),
            quantity: (int) ($this->value($row, 'quantity') ?? 0),
            weightValue: $this->float($row, 'weight_value'),
            weightUnit: $this->value($row, 'weight_unit'),
            requiresShipping: $this->boolean($row, 'requires_shipping', default: true),
            imageUrl: $this->value($row, 'variant_image_url'),
        );
    }

    /**
     * @param  array<string, ?string>  $row
     * @return array<int, string>
     */
    private function imageUrls(array $row): array
    {
        $value = $this->value($row, 'image_url');

        if ($value === null) {
            return [];
        }

        return array_values(array_filter(array_map(mb_trim(...), explode(';', $value))));
    }

    /**
     * @param  array<string, ?string>  $row
     */
    private function isVariantRow(array $row, int $index): bool
    {
        return $index === 0
            || $this->value($row, 'sku') !== null
            || $this->value($row, 'price') !== null
            || $this->value($row, 'option1_value') !== null;
    }

    /**
     * @param  array<string, ?string>  $row
     */
    private function value(array $row, string $key): ?string
    {
        $value = mb_trim((string) ($row[$this->column($key)] ?? ''));

        return $value === '' ? null : $value;
    }

    /**
     * @param  array<string, ?string>  $row
     */
    private function boolean(array $row, string $key, bool $default = false): bool
    {
        $value = $this->value($row, $key);

        return $value === null
            ? $default
            : filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param  array<string, ?string>  $row
     */
    private function float(array $row, string $key): ?float
    {
        $value = $this->value($row, $key);

        return $value === null ? null : (float) $value;
    }
}
