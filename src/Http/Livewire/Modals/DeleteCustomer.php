<?php

namespace Shopper\Framework\Http\Livewire\Modals;

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

        session()->flash('success', __("You have successfully archived this customer, it's no longer available in your customer list."));

        $this->redirectRoute('shopper.customers.index');
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render()
    {
        return view('shopper::livewire.modals.delete-customer');
    }
}
