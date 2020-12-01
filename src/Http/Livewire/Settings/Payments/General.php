<?php

namespace Shopper\Framework\Http\Livewire\Settings\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\PaymentMethod;

class General extends Component
{
    use WithPagination;

    /**
     * Search word
     *
     * @var string
     */
    public $search = '';

    /**
     * Title of the payment method.
     *
     * @var string
     */
    public $title;

    /**
     * Payment URL website, useful for documentation.
     *
     * @var string
     */
    public $linkUrl;

    /**
     * Description of the payment method.
     *
     * @var string
     */
    public $description;

    /**
     * Instructions to define how to use payment method.
     *
     * @var string
     */
    public $instructions;

    /**
     * Determine if the provider is enabled or not.
     *
     * @var bool
     */
    public $enabled = false;

    /**
     * Payment Method ID for edition.
     *
     * @var int
     */
    public $providerId;

    /**
     * Add a new entry of payment method in the storage.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'title' => 'required|unique:'. shopper_table('payment_methods'),
        ]);

        PaymentMethod::query()->create([
            'title' => $this->title,
            'link_url' => $this->linkUrl,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'is_enabled' => $this->enabled,
        ]);

        $this->dispatchBrowserEvent('modal-close');

        $this->dispatchBrowserEvent('notify', [
            'title' => __("Saved!"),
            'message' => __("Your payment method have been correctly added."),
        ]);

        $this->title = '';
        $this->linkUrl = '';
        $this->description = '';
        $this->instructions = '';
        $this->enabled = false;
    }

    /**
     * Display edition modal with full filled data.
     *
     * @param  int  $id
     * @return void
     */
    public function modalEdit(int $id)
    {
        $payment = PaymentMethod::query()->find($id);

        $this->providerId = $id;
        $this->title = $payment->title;
        $this->description = $payment->description;
        $this->linkUrl = $payment->link_url;
        $this->instructions = $payment->instructions;
        $this->enabled = $payment->is_enabled;

        $this->dispatchBrowserEvent('modal-open');
        $this->dispatchBrowserEvent('item-update');
    }

    /**
     * Close Modal.
     *
     * @return void
     */
    public function closeModal()
    {
        $this->dispatchBrowserEvent('modal-close');

        $this->providerId = null;
        $this->title = '';
        $this->linkUrl = '';
        $this->description = '';
        $this->instructions = '';
        $this->enabled = false;
    }

    /**
     * Update the current Payment on the modal.
     *
     * @return void
     */
    public function updatePayment()
    {
        $this->validate(['title' => 'sometimes|required']);

        PaymentMethod::query()
            ->find($this->providerId)
            ->update([
                'title' => $this->title,
                'link_url' => $this->linkUrl,
                'description' => $this->description,
                'instructions' => $this->instructions,
                'is_enabled' => $this->enabled,
            ]);

        $this->dispatchBrowserEvent('modal-close');

        $this->dispatchBrowserEvent('notify', [
            'title' => __("Update"),
            'message' => __("Your payment method have been correctly updated."),
        ]);

        $this->title = '';
        $this->linkUrl = '';
        $this->description = '';
        $this->instructions = '';
        $this->enabled = false;
    }

    /**
     * Removed item from the storage.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function removePayment(int $id)
    {
        PaymentMethod::query()->find($id)->delete();

        $this->dispatchBrowserEvent('item-update');

        $this->dispatchBrowserEvent('notify', [
            'title' => __("Deleted"),
            'message' => __("Your payment method have been correctly removed."),
        ]);
    }

    /**
     * Renter component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.payments.general', [
            'methods' => PaymentMethod::query()
                ->where('title', 'like', '%'. $this->search .'%')
                ->where('slug', '<>', 'stripe')
                ->orderByDesc('title')
                ->paginate(6)
        ]);
    }
}
