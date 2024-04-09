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
use Shopper\Core\Models\Product;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Edit extends AbstractPageComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Product $product;

    public function deleteAction(): Action
    {
        return Action::make(__('shopper::layout.forms.actions.delete'))
            ->requiresConfirmation()
            ->icon('untitledui-trash-03')
            ->modalIcon('untitledui-trash-03')
            ->color('danger')
            ->button()
            ->action(function (): void {
                $this->product->delete();

                Notification::make()
                    ->title(__('shopper::components.tables.messages.delete', ['name' => __('shopper::words.product')]))
                    ->success()
                    ->send();

                $this->redirectRoute(name: 'shopper.products.index', navigate: true);
            });
    }

    #[On('productHasUpdated')]
    public function render(): View
    {
        return view('shopper::livewire.pages.products.edit')
            ->title(__('shopper::words.actions_label.edit', ['name' => $this->product->name]));
    }
}
