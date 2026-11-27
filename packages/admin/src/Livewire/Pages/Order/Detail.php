<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Form\MoneyInput;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Events\Orders\OrderArchived;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Events\Orders\OrderCompleted;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Payment\Enum\RefundReason;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Services\PaymentProcessingService;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Traits\WithBreadcrumbs;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Detail extends AbstractPageComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use WithBreadcrumbs;

    #[Locked]
    public Order $order;

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: '#'.$this->order->number),
        ];
    }

    public function mount(): void
    {
        $this->authorize('orders.read');

        $this->order->load('customer', 'channel');
    }

    public function goToOrder(int $id): void
    {
        $this->redirectRoute('shopper.orders.detail', $id, navigate: true);
    }

    public function cancelOrderAction(): Action
    {
        return Action::make('cancelOrder')
            ->label(__('shopper::forms.actions.cancel_order'))
            ->authorize('orders.edit')
            ->visible($this->order->canBeCancelled() && ! $this->order->isCompleted())
            ->requiresConfirmation()
            ->modalHeading(__('shopper::pages/orders.modals.cancel_heading', ['number' => $this->order->number]))
            ->modalDescription(__('shopper::pages/orders.modals.cancel_notice'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.confirm'))
            ->action(function (): void {
                $this->order->transitionTo(OrderStatus::Cancelled);

                $this->order->refresh();
                $this->dispatch('order.updated');

                event(new OrderCancelled($this->order));

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.cancelled'))
                    ->success()
                    ->send();
            });
    }

    public function startProcessingAction(): Action
    {
        return Action::make('startProcessing')
            ->label(__('shopper::forms.actions.start_processing'))
            ->authorize('orders.edit')
            ->visible($this->order->isNew())
            ->requiresConfirmation()
            ->modalHeading(__('shopper::pages/orders.modals.processing_heading', ['number' => $this->order->number]))
            ->modalDescription(__('shopper::pages/orders.modals.processing_notice'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.confirm'))
            ->action(function (): void {
                $this->order->transitionTo(OrderStatus::Processing);

                $this->order->refresh();
                $this->dispatch('order.updated');

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.processing'))
                    ->success()
                    ->send();
            });
    }

    public function markPaidAction(): Action
    {
        return Action::make('markPaid')
            ->label(__('shopper::forms.actions.mark_paid'))
            ->authorize('orders.edit')
            ->visible(
                $this->order->isNotCancelled()
                && ! $this->order->isArchived()
                && ($this->order->isPaymentPending() || $this->order->payment_status === PaymentStatus::Voided)
            )
            ->requiresConfirmation()
            ->modalHeading(__('shopper::pages/orders.modals.paid_heading', ['number' => $this->order->number]))
            ->modalDescription(__('shopper::pages/orders.modals.paid_notice'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.confirm'))
            ->action(function (): void {
                $this->order->transitionPaymentTo(PaymentStatus::Paid);

                if ($this->order->isNew()) {
                    $this->order->transitionTo(OrderStatus::Processing);
                }

                $this->order->refresh();
                $this->dispatch('order.updated');

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.paid'))
                    ->success()
                    ->send();
            });
    }

    public function markCompleteAction(): Action
    {
        return Action::make('markComplete')
            ->label(__('shopper::forms.actions.mark_complete'))
            ->authorize('orders.edit')
            ->visible($this->order->isProcessing() && $this->order->isPaid())
            ->requiresConfirmation()
            ->modalHeading(__('shopper::pages/orders.modals.complete_heading', ['number' => $this->order->number]))
            ->modalDescription(__('shopper::pages/orders.modals.complete_notice'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.confirm'))
            ->action(function (): void {
                $this->order->transitionTo(OrderStatus::Completed);

                $this->order->refresh();
                $this->dispatch('order.updated');

                event(new OrderCompleted($this->order));

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.completed'))
                    ->success()
                    ->send();
            });
    }

    public function capturePaymentAction(): Action
    {
        return Action::make('capturePayment')
            ->label(__('shopper::forms.actions.capture_payment'))
            ->icon(Untitledui::CreditCardDown)
            ->authorize('orders.edit')
            ->visible($this->order->isPaymentAuthorized() && $this->order->isNotCancelled() && ! $this->order->isArchived() && $this->order->paymentMethod !== null)
            ->requiresConfirmation()
            ->modalIcon(Untitledui::CreditCardDown)
            ->modalHeading(__('shopper::pages/orders.modals.capture_heading', ['number' => $this->order->number]))
            ->modalDescription(__('shopper::pages/orders.modals.capture_notice'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.confirm'))
            ->action(function (): void {
                $service = resolve(PaymentProcessingService::class);
                $reference = $service->getLatestReference($this->order);

                if (! $reference) {
                    Notification::make()
                        ->title(__('shopper::pages/orders.notifications.capture_no_reference'))
                        ->danger()
                        ->send();

                    return;
                }

                $result = $service->capture($this->order, $reference);

                if (! $result->success) {
                    Notification::make()
                        ->title($result->message ?? __('shopper::pages/orders.notifications.capture_failed'))
                        ->danger()
                        ->send();

                    return;
                }

                $this->order->refresh();
                $this->dispatch('order.updated');

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.captured'))
                    ->success()
                    ->send();
            });
    }

    public function refundPaymentAction(): Action
    {
        $service = resolve(PaymentProcessingService::class);
        $currency = $this->order->currency_code;
        $unit = is_no_division_currency($currency) ? 1 : 100;

        return Action::make('refundPayment')
            ->label(__('shopper::forms.actions.refund'))
            ->icon(Untitledui::ReverseLeft)
            ->color('gray')
            ->authorize('orders.edit')
            ->visible(fn (): bool => $this->order->payment_status->canTransitionTo(PaymentStatus::Refunded)
                && $this->order->paymentMethod !== null
                && Payment::driver($this->order->paymentMethod->driver ?? 'manual')->supportsRefunds()
                && $service->refundableAmount($this->order) > 0)
            ->modalIcon(Untitledui::ReverseLeft)
            ->modalHeading(__('shopper::pages/orders.modals.refund_heading', ['number' => $this->order->number]))
            ->modalDescription(__('shopper::pages/orders.modals.refund_notice'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.refund'))
            ->modalWidth(Width::Large)
            ->fillForm(fn (): array => [
                'amount' => $service->refundableAmount($this->order),
                'reason' => RefundReason::RequestedByCustomer->value,
                'idempotency_key' => Str::uuid()->toString(),
            ])
            ->schema(function () use ($service, $currency, $unit): array {
                $refundable = $service->refundableAmount($this->order);

                return [
                    MoneyInput::make('amount')
                        ->label(__('shopper::words.amount'))
                        ->currency($currency)
                        ->required()
                        ->minValue(1 / $unit)
                        ->maxValue($refundable / $unit)
                        ->helperText(__('shopper::pages/orders.refund.refundable', [
                            'refundable' => shopper_money_format($refundable, $currency),
                            'captured' => shopper_money_format($service->capturedTotal($this->order), $currency),
                        ])),
                    Select::make('reason')
                        ->label(__('shopper::pages/orders.refund.reason'))
                        ->options(collect(RefundReason::cases())->mapWithKeys(fn (RefundReason $reason): array => [
                            $reason->value => __('shopper::pages/orders.refund.reasons.'.$reason->value),
                        ]))
                        ->required()
                        ->native(false),
                    Hidden::make('idempotency_key')->required()->uuid(),
                ];
            })
            ->action(function (array $data, Action $action) use ($service): void {
                $driver = Payment::driver($this->order->paymentMethod->driver ?? 'manual');
                $reference = $service->getLatestReference($this->order)
                    ?? ($driver->code() === 'manual' ? $this->order->number : null);

                if (! $reference) {
                    Notification::make()
                        ->title(__('shopper::pages/orders.notifications.capture_no_reference'))
                        ->danger()
                        ->send();

                    return;
                }

                try {
                    $result = $service->refund(
                        order: $this->order,
                        reference: $reference,
                        amount: (int) $data['amount'],
                        reason: RefundReason::from($data['reason']),
                        idempotencyKey: $data['idempotency_key'],
                    );
                } catch (PaymentException $e) {
                    Notification::make()
                        ->title(__('shopper::pages/orders.notifications.refund_failed'))
                        ->body($e->getMessage())
                        ->danger()
                        ->send();

                    $action->halt();

                    return;
                }

                if (! $result->success) {
                    Notification::make()
                        ->title(__('shopper::pages/orders.notifications.refund_failed'))
                        ->body($result->message)
                        ->danger()
                        ->send();

                    return;
                }

                $this->order->refresh();
                $this->dispatch('order.updated');

                Notification::make()
                    ->title(__($result->isPending()
                        ? 'shopper::pages/orders.notifications.refund_pending'
                        : 'shopper::pages/orders.notifications.refunded'))
                    ->success()
                    ->send();
            });
    }

    public function archiveAction(): Action
    {
        return Action::make('archive')
            ->label(__('shopper::forms.actions.archive'))
            ->color('danger')
            ->icon(Untitledui::Archive)
            ->authorize('orders.edit')
            ->visible(! $this->order->isCompleted() && ! $this->order->isPaid() && ! $this->order->isArchived())
            ->requiresConfirmation()
            ->modalHeading(__('shopper::pages/orders.modals.archived_number', ['number' => $this->order->number]))
            ->modalDescription(__('shopper::pages/orders.modals.archived_notice'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.confirm'))
            ->action(function (): void {
                $this->order->transitionTo(OrderStatus::Archived);

                event(new OrderArchived($this->order));

                Notification::make()
                    ->title(__('shopper::notifications.orders.archived'))
                    ->success()
                    ->send();

                $this->redirectRoute('shopper.orders.index', navigate: true);
            });
    }

    #[On('order.shipping.created')]
    public function render(): View
    {
        return view('shopper::livewire.pages.orders.detail', [
            'nextOrder' => resolve(Order::class)::query()
                ->where('id', '>', $this->order->id)
                ->oldest('id')
                ->first(),
            'prevOrder' => resolve(Order::class)::query()
                ->where('id', '<', $this->order->id)
                ->latest('id')
                ->first(),
        ])
            ->title(__('shopper::pages/orders.show_title', ['number' => $this->order->number]));
    }
}
