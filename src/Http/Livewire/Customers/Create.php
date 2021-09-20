<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Livewire\Component;
use Shopper\Framework\Rules\Phone;
use Illuminate\Support\Facades\Hash;
use Shopper\Framework\Traits\WithAddress;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Repositories\UserRepository;
use Shopper\Framework\Notifications\CustomerSendCredentials;

class Create extends Component
{
    use WithAddress;

    public string $last_name = '';

    public string $first_name = '';

    public string $email = '';

    public string $phone_number = '';

    public bool $opt_in = false;

    public bool $send_mail = false;

    public $password;

    public $password_confirmation;

    /**
     * Save new entry to the database.
     */
    public function store()
    {
        $this->validate($this->rules());

        $customer = (new UserRepository())->create([
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone_number' => $this->phone_number,
            'email_verified_at' => now()->toDateTimeString(),
            'opt_in' => $this->opt_in,
        ]);

        $customer->assignRole(config('shopper.system.users.default_role'));

        $customer->addresses()->create([
            'first_name' => $this->address_first_name,
            'last_name' => $this->address_last_name,
            'company_name' => $this->company_name,
            'country_id' => $this->country_id,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'street_address' => $this->street_address,
            'street_address_plus' => $this->street_address_plus,
            'is_default' => true,
            'type' => 'shipping',
        ]);

        if ($this->send_mail) {
            $customer->notify(new CustomerSendCredentials($this->password));
        }

        session()->flash('success', __('Customer successfully added!'));

        $this->redirectRoute('shopper.customers.show', $customer);
    }

    /**
     * Generate a 10 random string characters for password.
     */
    public function generate()
    {
        $this->password = mb_substr(mb_strtoupper(uniqid(str_random(10))), 0, 10);
    }

    /**
     * Real-time component validation.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updated(string $field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Component validation rules.
     *
     * @return array<string>
     */
    public function rules(): array
    {
        return array_merge($this->addressRules(), [
            'email' => 'required|max:150|unique:' . shopper_table('users'),
            'first_name' => 'required',
            'last_name' => 'required',
            'country_id' => 'required',
            'street_address' => 'required',
            'password' => 'required|confirmed|min:8',
            'phone_number' => [
                'nullable',
                new Phone(),
            ],
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.customers.create', [
            'countries' => Country::query()->get(),
        ]);
    }
}
