<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\View;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\SlideOverWizard;
use Shopper\Components\Wizard\StepColumn;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Import\ImportManager;
use Shopper\Core\Import\ProductRow;
use Shopper\Core\Import\Sources\CsvSource;
use Shopper\Core\Import\StartProductImport;
use Shopper\Core\Models\ProductImport;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class ImportCsv extends SlideOverComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public const PREVIEW_LIMIT = 50;

    public const MAPPABLE_FIELDS = [
        'name',
        'handle',
        'description',
        'brand',
        'category',
        'tags',
        'published',
        'sku',
        'barcode',
        'ean',
        'upc',
        'price',
        'compare_at_price',
        'cost_per_item',
        'currency',
        'quantity',
        'weight_value',
        'weight_unit',
        'seo_title',
        'seo_description',
    ];

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** @var array<int, string> */
    public array $fileHeaders = [];

    /** @var array<string, mixed> */
    public array $preview = [];

    public static function panelMaxWidth(): string
    {
        return '4xl';
    }

    public function mount(): void
    {
        $this->authorize('products.create');

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SlideOverWizard::make([
                    StepColumn::make(__('shopper::pages/products.import.steps.upload'))
                        ->icon(Untitledui::UploadCloud02)
                        ->schema([
                            FileUpload::make('file')
                                ->label(__('shopper::pages/products.import.file'))
                                ->helperText(__('shopper::pages/products.import.file_helper'))
                                ->required()
                                ->storeFiles(false)
                                ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel'])
                                ->hintAction(
                                    \Filament\Actions\Action::make('downloadTemplate')
                                        ->label(__('shopper::pages/products.import.download_template'))
                                        ->color('info')
                                        ->url(shopper_panel_assets('/templates/product-import.csv'), shouldOpenInNewTab: true),
                                ),
                        ])
                        ->afterValidation(function (): void {
                            $this->loadFileHeaders();
                        }),
                    StepColumn::make(__('shopper::pages/products.import.steps.mapping'))
                        ->icon(Untitledui::Dataflow04)
                        ->description(__('shopper::pages/products.import.mapping_description'))
                        ->schema([
                            Grid::make()
                                ->schema($this->mappingFields())
                                ->columns(2),
                        ])
                        ->afterValidation(function (): void {
                            $this->buildPreview();
                        }),
                    StepColumn::make(__('shopper::pages/products.import.steps.review'))
                        ->icon(Untitledui::CheckVerified02)
                        ->schema([
                            View::make('shopper::livewire.slide-overs.import-csv-preview'),
                        ]),
                ])
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button type="submit" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                            {{ __('shopper::pages/products.import.submit') }}
                        </x-filament::button>
                     BLADE))),
            ])
            ->statePath('data');
    }

    public function store(): void
    {
        $this->validate();

        $file = $this->uploadedFile();

        if (! $file instanceof TemporaryUploadedFile) {
            return;
        }

        $path = $file->storeAs(
            path: 'shopper/imports',
            name: str()->ulid().'-'.$file->getClientOriginalName(),
            options: 'local'
        );

        $import = ProductImport::query()->create([
            'source' => 'csv',
            'disk' => 'local',
            'file_path' => $path,
            'mapping' => array_filter($this->data['mapping'] ?? []),
            'status' => ImportStatus::Pending,
            'user_id' => auth()->id(),
        ]);

        resolve(StartProductImport::class)->execute($import);

        $this->dispatch('products.import.started');

        Notification::make()
            ->title(__('shopper::pages/products.import.started.title'))
            ->body(__('shopper::pages/products.import.started.body'))
            ->success()
            ->send();

        $this->closePanel();
    }

    public function render(): ViewContract
    {
        return view('shopper::livewire.slide-overs.import-csv');
    }

    protected function loadFileHeaders(): void
    {
        $file = $this->uploadedFile();

        if (! $file instanceof TemporaryUploadedFile) {
            return;
        }

        /** @var CsvSource $source */
        $source = resolve(ImportManager::class)->source('csv');

        $this->fileHeaders = $source->headers($file->getRealPath());

        foreach (self::MAPPABLE_FIELDS as $field) {
            if (blank($this->data['mapping'][$field] ?? null) && in_array($field, $this->fileHeaders, true)) {
                $this->data['mapping'][$field] = $field;
            }
        }
    }

    protected function buildPreview(): void
    {
        $file = $this->uploadedFile();

        if (! $file instanceof TemporaryUploadedFile) {
            return;
        }

        /** @var CsvSource $source */
        $source = resolve(ImportManager::class)->source('csv');
        $source = $source->withMapping(array_filter($this->data['mapping'] ?? []));

        $products = [];
        $totalProducts = 0;
        $totalVariants = 0;
        $totalStock = 0;
        $unnamed = 0;

        $source->read($file->getRealPath())->each(function (ProductRow $row) use (&$products, &$totalProducts, &$totalVariants, &$totalStock, &$unnamed): void {
            $totalProducts++;
            $totalVariants += count($row->variants);
            $totalStock += array_sum(array_map(fn ($variant): int => $variant->quantity, $row->variants));

            if ($row->name === '') {
                $unnamed++;
            }

            if (count($products) < self::PREVIEW_LIMIT) {
                $products[] = [
                    'name' => $row->name,
                    'brand' => $row->brand,
                    'price' => $row->variants[0]->price ?? null,
                    'variants_count' => count($row->variants),
                ];
            }
        });

        $this->preview = [
            'products' => $products,
            'total_products' => $totalProducts,
            'total_variants' => $totalVariants,
            'total_stock' => $totalStock,
            'unnamed' => $unnamed,
        ];
    }

    protected function uploadedFile(): ?TemporaryUploadedFile
    {
        $file = collect($this->data['file'] ?? [])->first();

        return $file instanceof TemporaryUploadedFile ? $file : null;
    }

    /**
     * @return array<int, Select>
     */
    protected function mappingFields(): array
    {
        return array_map(
            fn (string $field): Select => Select::make("mapping.{$field}")
                ->label(__("shopper::pages/products.import.fields.{$field}"))
                ->options(fn (): array => array_combine($this->fileHeaders, $this->fileHeaders))
                ->native(false)
                ->placeholder(__('shopper::pages/products.import.not_mapped'))
                ->required($field === 'name'),
            self::MAPPABLE_FIELDS
        );
    }
}
