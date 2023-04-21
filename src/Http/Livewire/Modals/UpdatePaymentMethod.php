<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\PaymentMethod;

class UpdatePaymentMethod extends ModalComponent
{
    use WithFileUploads;

    public PaymentMethod $paymentMethod;

    public string $title = '';

    public ?string $linkUrl = null;

    public ?string $description = null;

    public ?string $instructions = null;

    public ?string $logoUrl;

    public $logo;

    public function mount(int $id): void
    {
        $this->paymentMethod = $paymentMethod = PaymentMethod::find($id);
        $this->title = $paymentMethod->title;
        $this->description = $paymentMethod->description;
        $this->linkUrl = $paymentMethod->link_url;
        $this->instructions = $paymentMethod->instructions;
        $this->logoUrl = $paymentMethod->logo_url;
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function save(): void
    {
        $this->validate([
            'title' => [
                'required',
                Rule::unique(shopper_table('payment_methods'), 'title')->ignore($this->paymentMethod->id),
            ],
            'logo' => 'nullable|image|max:2048',
        ]);

        $this->paymentMethod->update([
            'title' => $this->title,
            'slug' => $this->title,
            'link_url' => $this->linkUrl,
            'description' => $this->description,
            'instructions' => $this->instructions,
        ]);

        if ($this->logo) {
            $this->paymentMethod->update([
                'logo' => $this->logo->store('/', config('shopper.system.storage.disks.uploads')),
            ]);
        }

        Notification::make()
            ->title(__('shopper::components.tables.status.updated'))
            ->body(__('Your payment method have been correctly updated'))
            ->success()
            ->send();

        $this->emit('onPaymentMethodAdded');

        $this->closeModal();
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.update-payment-method');
    }
}
