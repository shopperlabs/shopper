<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Core\Repositories\VariantRepository;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Variant extends AbstractPageComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $product;

    public $variant;

    public function mount(int $productId, int $variantId): void
    {
        $this->authorize('edit_products');

        $this->product = (new ProductRepository)->getById($productId);
        $this->variant = (new VariantRepository)
            ->with([
                'prices',
                'media',
                'values',
                'values.attribute',
            ])
            ->getById($variantId);
    }

    public function updateStockAction(): Action
    {
        return Action::make('updateStock')
            ->label(__('shopper::forms.actions.edit'))
            ->color('gray')
            ->modalWidth(MaxWidth::ExtraLarge)
            ->fillForm([
                'sku' => $this->variant->sku,
                'barcode' => $this->variant->barcode,
            ])
            ->record($this->variant)
            ->form([
                Forms\Components\TextInput::make('sku')
                    ->label(__('shopper::forms.label.sku'))
                    ->unique(config('shopper.models.variant'), 'sku', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('barcode')
                    ->label(__('shopper::forms.label.barcode'))
                    ->unique(config('shopper.models.variant'), 'barcode', ignoreRecord: true)
                    ->maxLength(255),
            ])
            ->action(function (array $data): void {
                $this->variant->update($data);

                Notification::make()
                    ->title(__('shopper::pages/products.notifications.variation_update'))
                    ->success()
                    ->send();
            });
    }

    public function mediaAction(): Action
    {
        return Action::make('media')
            ->label(__('shopper::forms.actions.edit'))
            ->color('gray')
            ->record($this->variant)
            ->fillForm($this->variant->toArray())
            ->modalWidth(MaxWidth::ThreeExtraLarge)
            ->form([
                Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->helperText(__('shopper::pages/products.thumbnail_helpText'))
                    ->image()
                    ->maxSize(config('shopper.media.max_size.thumbnail')),
                Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                    ->multiple()
                    ->label(__('shopper::words.images'))
                    ->panelLayout('grid')
                    ->helperText(__('shopper::pages/products.variant_images_helpText'))
                    ->collection(config('shopper.media.storage.collection_name'))
                    ->maxSize(config('shopper.media.max_size.images')),
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.products.variant')
            ->title(__('shopper::pages/products.variants.variant_title', ['name' => $this->variant->name]));
    }
}
