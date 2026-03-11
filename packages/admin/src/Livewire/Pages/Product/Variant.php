<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\Unique;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Variant extends AbstractPageComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?Product $product = null;

    public ?ProductVariant $variant = null;

    public function mount(): void
    {
        $this->authorize('edit_products');

        $this->variant?->load([
            'prices',
            'media',
            'values',
            'values.attribute',
        ]);
    }

    public function updateStockAction(): Action
    {
        return Action::make('updateStock')
            ->label(__('shopper::forms.actions.edit'))
            ->color('gray')
            ->modalWidth(Width::Large)
            ->fillForm([
                'sku' => $this->variant->sku,
                'barcode' => $this->variant->barcode,
            ])
            ->record($this->variant) // @phpstan-ignore-line
            ->schema([
                TextInput::make('sku')
                    ->label(__('shopper::forms.label.sku'))
                    ->unique(
                        table: config('shopper.models.variant'),
                        column: 'sku',
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule): Unique => $rule->where('product_id', $this->variant->product_id)
                    )
                    ->maxLength(255),
                TextInput::make('barcode')
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
            ->record($this->variant) // @phpstan-ignore-line
            ->fillForm($this->variant->toArray())
            ->modalWidth(Width::TwoExtraLarge)
            ->size(Size::Small)
            ->schema([
                SpatieMediaLibraryFileUpload::make('thumbnail')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->helperText(__('shopper::pages/products.thumbnail_helpText'))
                    ->image()
                    ->maxSize(config('shopper.media.max_size.thumbnail')),
                SpatieMediaLibraryFileUpload::make('images')
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
