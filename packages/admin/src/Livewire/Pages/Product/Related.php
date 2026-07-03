<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Lazy]
#[Layout('shopper::components.layouts.product')]
class Related extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /** @var Model&Product */
    #[Locked]
    public Product $product;

    public function mount(): void
    {
        $this->product->load('relatedProducts');
    }

    public function placeholder(): View
    {
        return view('shopper::components.skeleton.products.section');
    }

    /**
     * @param  array<int>  $ids
     */
    #[On('shopper.product.related.selected')]
    public function addRelatedProducts(array $ids): void
    {
        $this->authorize('products.edit');

        $this->product->relatedProducts()->syncWithoutDetaching($ids);
        $this->product->load('relatedProducts');

        Notification::make()
            ->title(__('shopper::layout.status.added'))
            ->body(__('shopper::pages/products.notifications.related_added'))
            ->success()
            ->send();
    }

    public function removeAction(): Action
    {
        return Action::make('remove')
            ->authorize('products.edit')
            ->label(__('shopper::forms.actions.remove'))
            ->icon(Untitledui::Trash03)
            ->color('danger')
            ->requiresConfirmation()
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

    /**
     * @return array<int>
     */
    #[Computed]
    public function productsIds(): array
    {
        return array_merge($this->product->relatedProducts->pluck('id')->toArray(), [$this->product->id]);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.related', [
            'relatedProducts' => $this->product->relatedProducts,
        ]);
    }
}
