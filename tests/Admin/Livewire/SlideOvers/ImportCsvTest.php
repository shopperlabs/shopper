<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Models\ProductImport;
use Shopper\Livewire\Pages\Product\Index;
use Shopper\Livewire\SlideOvers\ImportCsv;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.browse', 'products.create');
    $this->actingAs($this->user);
});

describe(ImportCsv::class, function (): void {
    it('blocks users without the products permission', function (): void {
        $this->actingAs(User::factory()->create());

        Livewire::test(ImportCsv::class)
            ->assertForbidden();
    });

    it('requires a file', function (): void {
        Livewire::test(ImportCsv::class)
            ->call('store')
            ->assertHasFormErrors(['file' => 'required']);
    });

    it('creates a pending import with its mapping and dispatches the batch', function (): void {
        Bus::fake();
        Storage::fake('local');

        $file = UploadedFile::fake()->createWithContent(
            'products.csv',
            (string) file_get_contents(__DIR__.'/../../../Core/Import/fixtures/products.csv')
        );

        Livewire::test(ImportCsv::class)
            ->set('fileHeaders', ['name', 'handle'])
            ->fillForm([
                'file' => $file,
                'mapping' => ['name' => 'name', 'handle' => 'handle'],
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertDispatched('products.import.started');

        $import = ProductImport::query()->sole();

        expect($import->source)->toBe('csv')
            ->and($import->mapping)->toBe(['name' => 'name', 'handle' => 'handle'])
            ->and($import->status)->toBe(ImportStatus::Processing)
            ->and($import->total_products)->toBe(3)
            ->and($import->user_id)->toBe($this->user->id);

        Bus::assertBatched(fn ($batch): bool => $batch->name === "product-import-{$import->id}");
    });
});

describe('products index import action', function (): void {
    it('opens the slide over matching the chosen source', function (): void {
        Livewire::test(Index::class)
            ->callAction('import', data: ['source' => 'csv'])
            ->assertDispatched('openPanel', 'shopper-slide-overs.import-csv');
    });
});
