<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Payments;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\PaymentMethod;
use WireUi\Traits\Actions;

class General extends Component
{
    use Actions;
    use WithPagination;

    public string $search = '';

    protected $listeners = ['onPaymentMethodAdded' => 'render'];

    public function toggleStatus(int $id, int $status): void
    {
        PaymentMethod::query()->find($id)->update(['is_enabled' => ! ($status === 1)]);

        $this->dispatchBrowserEvent('toggle-saved-' . $id);

        $this->notification()->success(__('Updated'), __('Your payment method status have been correctly updated.'));
    }

    public function removePayment(int $id): void
    {
        PaymentMethod::query()->find($id)->delete();

        $this->dispatchBrowserEvent('item-update');

        $this->notification()->success(__('Deleted'), __('Your payment method have been correctly removed.'));
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.payments.general', [
            'methods' => PaymentMethod::query()
                ->where('title', 'like', '%' . $this->search . '%')
                ->where('slug', '<>', 'stripe')
                ->orderByDesc('title')
                ->paginate(6),
        ]);
    }
}
