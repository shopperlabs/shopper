<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Core\Models\PaymentMethod;

#[Layout('shopper::components.layouts.setting')]
class Payment extends Component
{
    use WithPagination;

    public string $search = '';

    public function toggleStatus(int $id, int $status): void
    {
        PaymentMethod::query()
            ->find($id)
            ->update(['is_enabled' => ! ($status === 1)]);

        $this->dispatch('onPaymentMethodAdded');

        Notification::make()
            ->title(__('shopper::notifications.payment.update'))
            ->success()
            ->send();
    }

    public function removePayment(int $id): void
    {
        PaymentMethod::query()->find($id)->delete();

        $this->dispatch('onPaymentMethodAdded');

        Notification::make()
            ->title(__('shopper::notifications.actions.remove', ['item' => __('shopper::words.payment_method')]))
            ->success()
            ->send();
    }

    public function launchPaymentModal(int $id): void
    {

    }

    #[On('onPaymentMethodAdded')]
    public function render(): View
    {
        return view('shopper::livewire.pages.settings.payment', [
            'methods' => PaymentMethod::query()
                ->where('title', 'like', '%' . $this->search . '%')
                ->orderByDesc('title')
                ->paginate(6),
        ]);
    }
}
