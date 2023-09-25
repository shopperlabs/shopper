<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Models\PaymentMethod;

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

    public function save(): void
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
                'logo' => $this->logo->store('/', config('shopper.core.storage.collection_name')),
            ]);
        }

        Notification::make()
            ->body(__('shopper::notifications.payment.add'))
            ->success()
            ->send();

        $this->emit('onPaymentMethodAdded');

        $this->closeModal();
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.create-payment-method');
    }
}
