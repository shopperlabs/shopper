<?php

namespace Shopper\Framework\Http\Livewire\Settings\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\PaymentMethod;

class General extends Component
{
    use WithPagination;

    public string $search = '';

    protected $listeners = ['onPaymentMethodAdded' => 'render'];

    public function toggleStatus(int $id, int $status)
    {
        PaymentMethod::query()->find($id)->update(['is_enabled' => ! ($status === 1)]);

        $this->dispatchBrowserEvent('toggle-saved-'. $id);

        $this->notify([
            'title' => __('Update'),
            'message' => __('Your payment method status have been correctly updated.'),
        ]);
    }

    /**
     * Removed item from the storage.
     *
     * @param  int  $id
     *
     * @throws \Exception
     */
    public function removePayment(int $id)
    {
        PaymentMethod::query()->find($id)->delete();

        $this->dispatchBrowserEvent('item-update');

        $this->notify([
            'title' => __('Deleted'),
            'message' => __('Your payment method have been correctly removed.'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.settings.payments.general', [
            'methods' => PaymentMethod::query()
                ->where('title', 'like', '%'. $this->search .'%')
                ->where('slug', '<>', 'stripe')
                ->orderByDesc('title')
                ->paginate(6),
        ]);
    }
}
