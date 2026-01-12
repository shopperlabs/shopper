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
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Shopper\Core\Events\Products\ProductDeleted;
use Shopper\Core\Models\Product;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Edit extends AbstractPageComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?Product $product = null;

    #[Url(as: 'tab')]
    public string $activeTab = 'detail';

    public function mount(?Product $product = null): void
    {
        $this->authorize('edit_products');

        $this->product = $product?->load('prices');
    }

    public function deleteAction(): Action
    {
        return Action::make(__('shopper::forms.actions.delete'))
            ->requiresConfirmation()
            ->icon('untitledui-trash-03')
            ->modalIcon('untitledui-trash-03')
            ->authorize('delete_products', $this->product)
            ->color('danger')
            ->button()
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
