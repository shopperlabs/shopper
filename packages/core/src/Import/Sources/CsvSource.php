<?php

declare(strict_types=1);

namespace Shopper\Core\Import\Sources;

use Generator;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;
use League\Csv\SyntaxError;
use Shopper\Core\Exceptions\ProductImportException;
use Shopper\Core\Import\Contracts\ImportSource;
use Shopper\Core\Import\Contracts\SupportsColumnMapping;
use Shopper\Core\Import\ImageRow;
use Shopper\Core\Import\ProductRow;
use Shopper\Core\Import\VariantRow;

final class CsvSource implements ImportSource, SupportsColumnMapping
{
    private const array REQUIRED_COLUMNS = ['handle', 'name'];

    /** @var array<string, ?string> */
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

        try {
            $header = $reader->getHeader();
        } catch (SyntaxError $e) {
            throw new ProductImportException('The file has no header row.', previous: $e);
        }

        if ($header !== array_unique($header)) {
            throw new ProductImportException('Several columns of the file have the same name or no name.');
        }

        return array_values(array_filter($header));
    }

    public function withMapping(array $mapping): static
    {
        $clone = clone $this;
        $clone->mapping = $mapping;

        return $clone;
    }

    public function read(string $path): LazyCollection
    {
        return LazyCollection::make(function () use ($path): Generator {
            $reader = Reader::from($path);
            $reader->setHeaderOffset(0);

            $header = $reader->getHeader();

            $this->validateHeader($header);

            $fields = array_values(array_filter(
                array_unique([...array_keys($this->mapping), ...$header]),
                fn (string $field): bool => in_array($this->column($field), $header, true),
            ));

            $handle = null;
            $buffer = [];

            foreach ($reader->getRecords() as $record) {
                $rowHandle = mb_trim((string) ($record[$this->column('handle')] ?? ''));

                if ($rowHandle === '') {
                    continue;
                }

                if ($handle !== null && $rowHandle !== $handle && $buffer !== []) {
                    yield $this->makeProduct($buffer, $fields);
                    $buffer = [];
                }

                $handle = $rowHandle;
                $buffer[] = $record;
            }

            if ($buffer !== []) {
                yield $this->makeProduct($buffer, $fields);
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

    private function column(string $key): ?string
    {
        return array_key_exists($key, $this->mapping) ? $this->mapping[$key] : $key;
    }

    /**
     * @param  array<int, array<string, ?string>>  $rows
     * @param  array<int, string>  $fields
     */
    private function makeProduct(array $rows, array $fields): ProductRow
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
            published: $this->boolean($first, 'published'),
            seoTitle: $this->value($first, 'seo_title'),
            seoDescription: $this->value($first, 'seo_description'),
            optionNames: $optionNames,
            variants: $variants,
            images: $images,
            fields: $fields,
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
            quantity: $this->quantity($row),
            weightValue: $this->float($row, 'weight_value'),
            weightUnit: $this->value($row, 'weight_unit'),
            allowBackorder: $this->backorder($row),
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
        $column = $this->column($key);

        if ($column === null) {
            return null;
        }

        $value = mb_trim((string) ($row[$column] ?? ''));

        return $value === '' ? null : $value;
    }

    /**
     * @param  array<string, ?string>  $row
     */
    private function boolean(array $row, string $key): ?bool
    {
        $value = $this->value($row, $key);

        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value === 1;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            ?? throw ProductImportException::invalidBoolean(...$this->cell($row, $key, $value));
    }

    /**
     * @param  array<string, ?string>  $row
     */
    private function backorder(array $row): bool
    {
        return match (mb_strtolower((string) $this->value($row, 'allow_backorder'))) {
            'continue', 'notify' => true,
            'deny' => false,
            default => $this->boolean($row, 'allow_backorder') ?? false,
        };
    }

    /**
     * @param  array<string, ?string>  $row
     */
    private function float(array $row, string $key): ?float
    {
        $value = $this->value($row, $key);

        if ($value === null) {
            return null;
        }

        if (! is_numeric($value)) {
            throw ProductImportException::invalidNumber(...$this->cell($row, $key, $value));
        }

        return (float) $value;
    }

    /**
     * @param  array<string, ?string>  $row
     */
    private function quantity(array $row): ?int
    {
        $quantity = $this->float($row, 'quantity');

        if ($quantity !== null && floor($quantity) !== $quantity) {
            throw ProductImportException::invalidNumber(...$this->cell($row, 'quantity', (string) $this->value($row, 'quantity')));
        }

        return $quantity === null ? null : (int) $quantity;
    }

    /**
     * @param  array<string, ?string>  $row
     * @return array{handle: string, field: string, column: string, value: string}
     */
    private function cell(array $row, string $key, string $value): array
    {
        return [
            'handle' => (string) $this->value($row, 'handle'),
            'field' => $key,
            'column' => (string) $this->column($key),
            'value' => $value,
        ];
    }
}
