<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Shopper\Core\Repositories\UserRepository;
use Shopper\Http\Livewire\AbstractBaseComponent;

class Show extends AbstractBaseComponent
{
    public $customer;

    public int $user_id;

    public string $last_name;

    public string $first_name;

    public string $email;

    public string $picture;

    protected $listeners = ['profileUpdate'];

    public function mount($customer): void
    {
        $this->customer = $customer->load('addresses');
        $this->user_id = $customer->id;
        $this->email = $customer->email;
        $this->last_name = $customer->last_name;
        $this->first_name = $customer->first_name;
        $this->picture = $customer->picture;
    }

    public function profileUpdate(): void
    {
        $this->email = $this->customer->email;
        $this->last_name = $this->customer->last_name;
        $this->first_name = $this->customer->first_name;
        $this->picture = $this->customer->picture;
    }

    public function store(): void
    {
        $this->validate($this->rules());

        (new UserRepository())->getById($this->customer->id)->update([
            'email' => $this->email,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
        ]);

        session()->flash('success', __('shopper::notifications.actions.update', ['item' => __('shopper::words.customer')]));

        $this->redirectRoute('shopper.customers.index');
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'max:150',
                Rule::unique(shopper_table('users'), 'email')->ignore($this->user_id),
            ],
            'last_name' => 'sometimes|required',
            'first_name' => 'sometimes|required',
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.customers.show');
    }
}
