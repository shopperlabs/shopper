<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Events\Products\ProductDeleted;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Traits\HandlesAuthorizationExceptions;

class DeleteAction extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /** @var Model&ProductContract */
    #[Locked]
    public ProductContract $product;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('shopper::forms.actions.delete'))
            ->icon(Untitledui::Trash03)
            ->modalIcon(Untitledui::Trash03)
            ->requiresConfirmation()
            ->authorize('products.delete', $this->product)
            ->visible(shopper()->auth()->user()->can('products.delete'))
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

    public function render(): View
    {
        return view('shopper::livewire.components.products.delete-action');
    }
}
