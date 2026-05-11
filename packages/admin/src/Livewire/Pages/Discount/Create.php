<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Discount;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Shopper\Core\Models\Discount;
use Shopper\Livewire\Concerns\InteractsWithDiscountForm;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Traits\WithBreadcrumbs;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class Create extends AbstractPageComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithDiscountForm;
    use InteractsWithSchemas;
    use WithBreadcrumbs;

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::forms.actions.create')),
        ];
    }

    public function mount(): void
    {
        $this->authorize('discounts.create');

        $this->discount = new Discount;
        $this->form->fill();
    }

    #[Computed]
    public function summary(): array
    {
        return $this->buildSummary();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.discounts.create')
            ->title(__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/discounts.single')]));
    }
}
