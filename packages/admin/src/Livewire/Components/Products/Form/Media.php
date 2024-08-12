<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * @property Forms\Form $form
 */
class Media extends Component implements HasForms
{
    use InteractsWithForms;

    public $product;

    public ?array $data = [];

    public function mount($product): void
    {
        $this->product = $product;

        $this->form->fill($this->product->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                    ->collection(config('shopper.core.storage.collection_name'))
                    ->label(__('shopper::words.images'))
                    ->helperText(__('shopper::pages/products.images_helpText'))
                    ->multiple()
                    ->panelLayout('grid')
                    ->columnSpan(['lg' => 2]),

                Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                    ->collection(config('shopper.core.storage.thumbnail_collection'))
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->helperText(__('shopper::pages/products.thumbnail_helpText'))
                    ->image()
                    ->maxSize(1024)
                    ->imageEditor()
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3)
            ->statePath('data')
            ->model($this->product);
    }

    public function store(): void
    {
        $this->product->update($this->form->getState());

        $this->dispatch('productHasUpdated');

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
