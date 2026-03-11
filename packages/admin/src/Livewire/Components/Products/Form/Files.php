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
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class Files extends Component implements HasSchemas
{
    use HandlesAuthorizationExceptions;
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
                SpatieMediaLibraryFileUpload::make('files')
                    ->collection('files')
                    ->label(__('shopper::words.files'))
                    ->helperText(__('shopper::pages/products.files_helpText'))
                    ->multiple()
                    ->panelLayout('grid')
                    ->columnSpan(['lg' => 2]),
            ])
            ->columns(3)
            ->statePath('data')
            ->model($this->product);
    }

    public function store(): void
    {
        $this->product->update($this->form->getState());

        $this->dispatch('product.updated');

        Notification::make()
            ->body(__('shopper::pages/products.notifications.files_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.files');
    }
}
