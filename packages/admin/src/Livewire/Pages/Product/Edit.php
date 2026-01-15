<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Shopper\Core\Events\Products\ProductDeleted;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Edit extends AbstractPageComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    /** @var Model&ProductContract */
    public ProductContract $product;

    #[Url(as: 'tab')]
    public string $activeTab = 'detail';

    public function mount(): void
    {
        $this->authorize('edit_products');

        $this->product?->load('prices');
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('shopper::forms.actions.delete'))
            ->icon('untitledui-trash-03')
            ->modalIcon('untitledui-trash-03')
            ->requiresConfirmation()
            ->authorize('delete_products', $this->product)
            ->visible(shopper()->auth()->user()->can('delete_products'))
            ->color('danger')
            ->action(function (): void {
                event(new ProductDeleted($this->product));

                $this->product->delete();

                Notification::make()
                    ->title(__('shopper::notifications.delete', ['item' => __('shopper::pages/products.single')]))
                    ->success()
                    ->send();

                $this->redirectRoute(name: 'shopper.products.index', navigate: true);
            });
    }

    #[On('product.updated')]
    public function render(): View
    {
        return view('shopper::livewire.pages.products.edit')
            ->title(__('shopper::forms.actions.edit_label', ['label' => $this->product->name]));
    }
}
