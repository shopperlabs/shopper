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
            new Breadcrumb(text: $this->discountLabel()),
        ];
    }

    public function discountLabel(): string
    {
        return $this->discount->code ?? __('shopper::pages/discounts.method_automatic');
    }

    public function mount(int $record): void
    {
        $this->authorize('discounts.edit');

        $this->discount = Discount::query()
            ->with([
                'zone',
                'campaign',
                'items:id,discount_id,discountable_type,discountable_id,condition',
            ])
            ->findOrFail($record);

        $items = $this->discount->items;

        $this->form->fill([
            'zone_id' => $this->discount->zone_id,
            'code' => $this->discount->code,
            'trigger' => $this->discount->trigger->value,
            'exclusivity_class' => $this->discount->exclusivity_class->value,
            'combinable' => $this->discount->combinable,
            'priority' => $this->discount->priority,
            'campaign_id' => $this->discount->campaign_id,
            'type' => $this->discount->type->value,
            'value' => $this->discount->value,
            'apply_to' => $this->discount->apply_to,
            'eligibility' => $this->discount->eligibility,
            'min_required' => $this->discount->min_required,
            'min_required_value' => $this->discount->min_required_value,
            'usage_limit' => $this->discount->usage_limit,
            'usage_limit_per_user' => $this->discount->usage_limit_per_user,
            'start_at' => $this->discount->start_at,
            'end_at' => $this->discount->end_at,
            'metadata' => $this->discount->metadata,
            'is_active' => $this->discount->is_active,
            'usage_number' => $this->discount->usage_limit !== null,
            'customers' => $items
                ->where('condition', DiscountCondition::Eligibility)
                ->pluck('discountable_id')
                ->map(fn ($id): int => (int) $id)
                ->all(),
            'products' => $items
                ->where('condition', DiscountCondition::ApplyTo)
                ->pluck('discountable_id')
                ->map(fn ($id): int => (int) $id)
                ->all(),
        ]);
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
            ->title($this->discountLabel());
    }
}
