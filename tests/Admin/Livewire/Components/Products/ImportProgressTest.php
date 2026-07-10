<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Models\ProductImport;
use Shopper\Livewire\Components\Products\ImportProgress;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

describe(ImportProgress::class, function (): void {
    it('renders nothing when no import is running', function (): void {
        Livewire::test(ImportProgress::class)
            ->assertDontSee(__('shopper::pages/products.import.progress.running'));
    });

    it('shows the running import with its counters', function (): void {
        ProductImport::query()->create([
            'source' => 'csv',
            'disk' => 'local',
            'file_path' => 'imports/products.csv',
            'status' => ImportStatus::Processing,
            'total_products' => 12,
            'imported_count' => 4,
        ]);

        Livewire::test(ImportProgress::class)
            ->assertSee(__('shopper::pages/products.import.progress.running'))
            ->assertSee(__('shopper::pages/products.import.progress.count', ['imported' => 4, 'total' => 12]));
    });

    it('dispatches the finished event when the watched import completes', function (): void {
        $import = ProductImport::query()->create([
            'source' => 'csv',
            'disk' => 'local',
            'file_path' => 'imports/products.csv',
            'status' => ImportStatus::Processing,
            'total_products' => 12,
        ]);

        $component = Livewire::test(ImportProgress::class);

        $import->update(['status' => ImportStatus::Completed]);

        $component->call('$refresh')
            ->assertDispatched('products.import.finished');
    });

    it('does not dispatch the finished event when no import was being watched', function (): void {
        Livewire::test(ImportProgress::class)
            ->call('$refresh')
            ->assertNotDispatched('products.import.finished');
    });

    it('hides finished imports', function (): void {
        ProductImport::query()->create([
            'source' => 'csv',
            'disk' => 'local',
            'file_path' => 'imports/products.csv',
            'status' => ImportStatus::Completed,
            'total_products' => 12,
            'imported_count' => 12,
        ]);

        Livewire::test(ImportProgress::class)
            ->assertDontSee(__('shopper::pages/products.import.progress.running'));
    });
});
