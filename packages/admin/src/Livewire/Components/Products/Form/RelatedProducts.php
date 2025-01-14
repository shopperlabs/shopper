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
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class RelatedProducts extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $product;

    public function mount($product): void
    {
        $this->product->load('relatedProducts');
    }

    public function placeholder(): View
    {
        return view('shopper::components.skeleton.products.section');
    }

    public function removeAction(): Action
    {
        return Action::make('remove')
            ->label(__('shopper::forms.actions.remove'))
            ->icon('untitledui-trash-03')
            ->action(function (array $arguments): void {
                $this->product->relatedProducts()->detach($arguments['id']);

                Notification::make()
                    ->title(__('shopper::pages/products.notifications.remove_related'))
                    ->success()
                    ->send();

                $this->redirect(
                    route('shopper.products.edit', ['product' => $this->product, 'tab' => 'related']),
                    navigate: true
                );
            });
    }

    #[Computed]
    public function productsIds(): array
    {
        return array_merge($this->product->relatedProducts->modelKeys(), [$this->product->id]);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.related', [
            'relatedProducts' => $this->product->relatedProducts,
        ]);
    }
}
