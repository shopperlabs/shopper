<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\UserRepository;

class DeleteCustomer extends ModalComponent
{
    public int $customerId;

    public function mount(int $customerId)
    {
        $this->customerId = $customerId;
    }

    public function delete()
    {
        (new UserRepository())->getById($this->customerId)->delete();

        session()->flash('success', __('shopper::pages/customers.modal.success_message'));

        $this->redirectRoute('shopper.customers.index');
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-customer');
    }
}
