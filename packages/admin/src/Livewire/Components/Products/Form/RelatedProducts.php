<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Lazy]
class RelatedProducts extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /** @var Model&Product */
    public Product $product;

    public function mount(): void
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
