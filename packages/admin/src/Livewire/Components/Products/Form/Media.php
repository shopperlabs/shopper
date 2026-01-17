<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Core\Models\Contracts\Product;

/**
 * @property-read Schema $form
 */
class Media extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    /** @var Model&Product */
    public Product $product;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->product->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('thumbnail')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->helperText(__('shopper::pages/products.thumbnail_helpText'))
                    ->image()
                    ->maxSize(config('shopper.media.max_size.thumbnail'))
                    ->columnSpan(['lg' => 1]),
                SpatieMediaLibraryFileUpload::make('images')
                    ->collection(config('shopper.media.storage.collection_name'))
                    ->label(__('shopper::words.images'))
                    ->helperText(__('shopper::pages/products.images_helpText'))
                    ->multiple()
                    ->panelLayout('grid')
                    ->maxSize(config('shopper.media.max_size.images'))
                    ->columnSpan(['lg' => 2]),
            ])
            ->columns(3)
            ->statePath('data')
            ->model($this->product);
    }

    public function store(): void
    {
        $this->validate();

        $this->product->update($this->form->getState());

        $this->dispatch('product.updated');

        Notification::make()
            ->body(__('shopper::pages/products.notifications.media_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.media');
    }
}
