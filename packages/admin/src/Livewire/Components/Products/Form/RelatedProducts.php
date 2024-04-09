<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class RelatedProducts extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $product;

    public function mount($product): void
    {
        $this->product = $product;
    }

    public function removeAction(): Action
    {
        return Action::make('remove')
            ->label(__('shopper::layout.forms.actions.remove'))
            ->icon('untitledui-trash-03')
            ->action(function (array $arguments): void {
                $this->product->relatedProducts()->detach($arguments['id']);

                $this->dispatch('productHasUpdated');

                Notification::make()
                    ->title(__('shopper::pages/products.notifications.remove_related'))
                    ->success()
                    ->send();
            });
    }

    #[Computed]
    public function productsIds(): array
    {
        return array_merge($this->product->relatedProducts->modelKeys(), [$this->product->id]);
    }

    #[On('productHasUpdated')]
    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.related', [
            'relatedProducts' => $this->product->relatedProducts,
        ]);
    }
}
