<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Validation\Rule;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\UserRepository;

class Show extends AbstractBaseComponent
{
    /**
     * Customer Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $customer;

    public int $user_id;

    public string $last_name;

    public string $first_name;

    public string $email;

    public string $picture;

    protected $listeners = ['profileUpdate'];

    /**
     * Component mounted action.
     *
     * @param $customer
     */
    public function mount($customer)
    {
        $this->customer = $customer;
        $this->user_id = $customer->id;
        $this->email = $customer->email;
        $this->last_name = $customer->last_name;
        $this->first_name = $customer->first_name;
        $this->picture = $customer->picture;
    }

    /**
     * Update Customer profile after listen to custom event.
     */
    public function profileUpdate()
    {
        $this->email = $this->customer->email;
        $this->last_name = $this->customer->last_name;
        $this->first_name = $this->customer->first_name;
        $this->picture = $this->customer->picture;
    }

    /**
     * Update customer record in the database.
     */
    public function store()
    {
        $this->validate($this->rules());

        (new UserRepository())->getById($this->customer->id)->update([
            'email' => $this->email,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
        ]);

        session()->flash('success', __('Customer successfully updated!'));

        $this->redirectRoute('shopper.customers.index');
    }

    /**
     * Component validation rules.
     *
     * @return array<string>
     */
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

    public function render()
    {
        return view('shopper::livewire.customers.show');
    }
}
