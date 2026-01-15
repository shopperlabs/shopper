<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Components;
use Shopper\Core\Models\Contracts\Product as ProductContract;

/**
 * @property-read Form $form
 */
class Seo extends Component implements HasForms
{
    use InteractsWithForms;

    /** @var Model&ProductContract */
    public ProductContract $product;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->product->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema(Components\Form\SeoField::make()),
                Forms\Components\KeyValue::make('metadata')
                    ->reorderable(),
            ])
            ->statePath('data')
            ->model($this->product);
    }

    public function store(): void
    {
        $this->product->update($this->form->getState());

        $this->dispatch('product.updated');

        Notification::make()
            ->body(__('shopper::pages/products.notifications.seo_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.seo');
    }
}
