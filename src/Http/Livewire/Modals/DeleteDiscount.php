<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\Discount;

class DeleteDiscount extends ModalComponent
{
    public int $discountID;

    public function mount(int $discountID): void
    {
        $this->discountID = $discountID;
    }

    public function delete(): void
    {
        Discount::query()->find($this->discountID)->delete();

        session()->flash('success', __('shopper::pages/discounts.modals.remove.success_message'));

        $this->redirectRoute('shopper.discounts.index');
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-discount');
    }
}
