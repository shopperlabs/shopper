<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Discount;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Actions\Store\DuplicateDiscountAction;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Models\Discount;
use Shopper\Livewire\Concerns\InteractsWithDiscountForm;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Traits\WithBreadcrumbs;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class Edit extends AbstractPageComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithDiscountForm;
    use InteractsWithSchemas;
    use WithBreadcrumbs;

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: $this->discount->code),
        ];
    }

    public function mount(int $record): void
    {
        $this->authorize('discounts.edit');

        $this->discount = Discount::query()
            ->with(['zone', 'items.discountable'])
            ->findOrFail($record);

        $items = $this->discount->items;

        $this->form->fill(array_merge(
            $this->discount->toArray(),
            ['usage_number' => $this->discount->usage_limit !== null],
            [
                'customers' => $items
                    ->where('condition', DiscountCondition::Eligibility)
                    ->map(fn ($item) => $item->discountable)
                    ->filter()
                    ->pluck('id')
                    ->all(),
            ],
            [
                'products' => $items
                    ->where('condition', DiscountCondition::ApplyTo)
                    ->map(fn ($item) => $item->discountable)
                    ->filter()
                    ->pluck('id')
                    ->all(),
            ],
        ));
    }

    #[Computed]
    public function summary(): array
    {
        return $this->buildSummary();
    }

    public function duplicateAction(): Action
    {
        return Action::make('duplicate')
            ->label(__('shopper::pages/discounts.actions.duplicate'))
            ->icon(Untitledui::Copy03)
            ->color('gray')
            ->requiresConfirmation()
            ->modalHeading(__('shopper::pages/discounts.actions.duplicate_confirm_heading'))
            ->modalDescription(__('shopper::pages/discounts.actions.duplicate_confirm_description'))
            ->authorize('discounts.create')
            ->action(function (): void {
                $this->authorize('discounts.create');

                $lock = Cache::lock(
                    "discount:duplicate:{$this->discount->id}:".shopper()->auth()->id(),
                    seconds: 5,
                );

                if (! $lock->get()) {
                    Notification::make()
                        ->title(__('shopper::pages/discounts.actions.duplicate_in_progress'))
                        ->warning()
                        ->send();

                    return;
                }

                try {
                    $clone = resolve(DuplicateDiscountAction::class)($this->discount);
                } finally {
                    $lock->release();
                }

                Notification::make()
                    ->title(__('shopper::pages/discounts.actions.duplicate_success', ['code' => $clone->code]))
                    ->success()
                    ->send();

                $this->redirectRoute(
                    name: 'shopper.discounts.edit',
                    parameters: ['record' => $clone->id],
                    navigate: true,
                );
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('shopper::forms.actions.delete'))
            ->icon(Untitledui::Trash03)
            ->color('danger')
            ->requiresConfirmation()
            ->authorize('discounts.delete')
            ->action(function (): void {
                $this->authorize('discounts.delete');

                $code = $this->discount->code;
                $this->discount->delete();

                Notification::make()
                    ->title(__('shopper::notifications.delete', [
                        'item' => __('shopper::pages/discounts.single').' '.$code,
                    ]))
                    ->success()
                    ->send();

                $this->redirectRoute(name: 'shopper.discounts.index', navigate: true);
            });
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.discounts.edit')
            ->title($this->discount->code);
    }

}
