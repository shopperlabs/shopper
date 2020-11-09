<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\CustomerRepository;

class Create extends Component
{
    /**
     * Customer Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $customer;

    /**
     * Customer Model id.
     *
     * @var int
     */
    public $user_id;

    /**
     * Last Name attribute.
     *
     * @var string
     */
    public $last_name = '';

    /**
     * First Name attribute.
     *
     * @var string
     */
    public $first_name = '';

    /**
     * Email for custom url.
     *
     * @var string
     */
    public $email;

    public function store()
    {
        $this->validate($this->rules());

        $customer = (new CustomerRepository())->create([
            'email' => $this->email,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
        ]);

        session()->flash('success', __("Customer successfully added!"));
        $this->redirectRoute('shopper.customers.index');
    }

    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    public function rules()
    {
        return [
            'email'      => 'required|max:150|unique:'.shopper_table('users'),
            'first_name' => 'required',
            'last_name'  => 'required',
        ];
    }

    public function render()
    {
        return view('shopper::livewire.customers.create');
    }
}
