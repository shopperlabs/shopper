<?php

namespace Shopper\Framework\Http\Livewire\Settings\Payments;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\PaymentMethod;

class General extends Component
{
    use WithPagination, WithFileUploads;

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
     * Logo Attribute.
     *
     * @var mixed
     */
    public $logo;

    /**
     * Logo full url preview.
     *
     * @var string
     */
    public $logoUrl;

    /**
     * Add a new entry of payment method in the storage.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'title' => 'required|unique:'. shopper_table('payment_methods'),
            'logo'  => 'nullable|image|max:1024'
        ]);

        $paymentMethod = PaymentMethod::query()->create([
            'title' => $this->title,
            'link_url' => $this->linkUrl,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'is_enabled' => $this->enabled,
        ]);

        if ($this->logo) {
            $paymentMethod->update([
                'logo' => $this->logo->store('/', config('shopper.system.storage.disks.uploads'))
            ]);
        }

        $this->dispatchBrowserEvent('modal-close');

        $this->notify([
            'title' => __("Saved!"),
            'message' => __("Your payment method have been correctly added."),
        ]);

        $this->resetFields();
    }

    /**
     * Display edition modal with full filled data.
     *
     * @param  int  $id
     * @return void
     */
    public function modalEdit(int $id)
    {
        $paymentMethod = PaymentMethod::query()->find($id);

        $this->providerId = $id;
        $this->title = $paymentMethod->title;
        $this->description = $paymentMethod->description;
        $this->linkUrl = $paymentMethod->link_url;
        $this->instructions = $paymentMethod->instructions;
        $this->enabled = $paymentMethod->is_enabled;
        $this->logoUrl = $paymentMethod->logo_url;

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

        $this->resetFields();
    }

    /**
     * Update the current Payment on the modal.
     *
     * @return void
     */
    public function updatePaymentMethod()
    {
        $this->validate([
            'title' => [
                'required',
                Rule::unique(shopper_table('payment_methods'), 'title')->ignore($this->providerId)
            ],
            'logo'  => 'nullable|image|max:1024'
        ]);

        $paymentMethod = PaymentMethod::query()->find($this->providerId);

        $paymentMethod->update([
            'title' => $this->title,
            'link_url' => $this->linkUrl,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'is_enabled' => $this->enabled,
        ]);

        if ($this->logo) {
            $paymentMethod->update([
                'logo' => $this->logo->store('/', config('shopper.system.storage.disks.uploads'))
            ]);
        }

        $this->dispatchBrowserEvent('modal-close');

        $this->notify([
            'title' => __("Update"),
            'message' => __("Your payment method have been correctly updated."),
        ]);

        $this->resetFields();
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

        $this->notify([
            'title' => __("Deleted"),
            'message' => __("Your payment method have been correctly removed."),
        ]);
    }

    /**
     * Reset Components Form Fields.
     *
     * @return void
     */
    private function resetFields()
    {
        $this->providerId = null;
        $this->title = '';
        $this->linkUrl = '';
        $this->description = '';
        $this->instructions = '';
        $this->enabled = false;
        $this->logoUrl = null;
        $this->logo = null;
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
