<?php

declare(strict_types=1);

use Illuminate\Support\LazyCollection;
use Shopper\Core\Import\Contracts\ImportSource;
use Shopper\Core\Import\ImportManager;
use Shopper\Core\Import\Sources\CsvSource;

uses(Tests\Core\TestCase::class);

function fakeImportSource(string $code, bool $configured = true): ImportSource
{
    return new class($code, $configured) implements ImportSource
    {
        public function __construct(private string $code, private bool $configured) {}

        public function code(): string
        {
            return $this->code;
        }

        public function name(): string
        {
            return ucfirst($this->code);
        }

        public function description(): string
        {
            return "Import from {$this->code}";
        }

        public function icon(): string
        {
            return 'phosphor-plug-duotone';
        }

        public function isConfigured(): bool
        {
            return $this->configured;
        }

        public function read(string $path): LazyCollection
        {
            return LazyCollection::empty();
        }
    };
}

describe(ImportManager::class, function (): void {
    it('resolves the built-in csv source by default', function (): void {
        expect(resolve(ImportManager::class)->source())
            ->toBeInstanceOf(CsvSource::class)
            ->code()->toBe('csv');
    });

    it('resolves a custom source registered by an addon', function (): void {
        $manager = resolve(ImportManager::class)
            ->extend('woocommerce', fn (): ImportSource => fakeImportSource('woocommerce'));

        expect($manager->source('woocommerce')->name())->toBe('Woocommerce')
            ->and($manager->availableSources())->toBe(['csv', 'woocommerce']);
    });

    it('throws for an unknown source', function (): void {
        resolve(ImportManager::class)->source('missing');
    })->throws(InvalidArgumentException::class);

    it('keeps only configured sources in the configured set', function (): void {
        $manager = resolve(ImportManager::class)
            ->extend('etsy', fn (): ImportSource => fakeImportSource('etsy', configured: false));

        expect($manager->configuredSources()->keys()->all())->toBe(['csv'])
            ->and($manager->isConfigured('etsy'))->toBeFalse()
            ->and($manager->isConfigured('missing'))->toBeFalse();
    });
});
