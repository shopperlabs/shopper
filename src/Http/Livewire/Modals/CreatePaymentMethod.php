<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\PaymentMethod;

class CreatePaymentMethod extends ModalComponent
{
    use WithFileUploads;

    public string $title = '';
    public ?string $linkUrl = null;
    public ?string $description = null;
    public ?string $instructions = null;
    public $logo;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|unique:' . shopper_table('payment_methods'),
            'logo' => 'nullable|image|max:2048',
        ]);

        $paymentMethod = PaymentMethod::query()->create([
            'title' => $this->title,
            'slug' => $this->title,
            'link_url' => $this->linkUrl,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'is_enabled' => true,
        ]);

        if ($this->logo) {
            $paymentMethod->update([
                'logo' => $this->logo->store('/', config('shopper.system.storage.disks.uploads')),
            ]);
        }

        $this->notify([
            'title' => __('Saved!'),
            'message' => __('Your payment method have been correctly added.'),
        ]);

        $this->emit('onPaymentMethodAdded');

        $this->closeModal();
    }

    public function render()
    {
        return view('shopper::livewire.modals.create-payment-method');
    }
}
