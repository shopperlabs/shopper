<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Notifications\CustomerSendCredentials;
use Shopper\Framework\Repositories\UserRepository;
use Shopper\Framework\Rules\Phone;
use Shopper\Framework\Traits\WithAddress;

class Create extends Component
{
    use WithAddress;

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
     * Customer email address.
     *
     * @var string
     */
    public $email = '';

    /**
     * Customer phone number.
     *
     * @var string
     */
    public $phone_number = '';

    /**
     * Customer generate password.
     *
     * @var string
     */
    public $password;

    /**
     * Customer password confirmation.
     *
     * @var string
     */
    public $password_confirmation;

    /**
     * Define if the user subscribe to marketing email.
     *
     * @var bool
     */
    public $opt_in = false;

    /**
     * Define if we send an email to the customer.
     *
     * @var bool
     */
    public $send_mail = false;

    /**
     * Save new entry to the database.
     *
     * @return void
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
     *
     * @return void
     */
    public function generate()
    {
        $this->password = substr(strtoupper(uniqid(str_random(10))), 0, 10);
    }

    /**
     * Real-time component validation.
     *
     * @param  string  $field
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return void
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
            'email' => 'required|max:150|unique:'.shopper_table('users'),
            'first_name' => 'required',
            'last_name' => 'required',
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
