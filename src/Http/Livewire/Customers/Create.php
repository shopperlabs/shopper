<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Notifications\CustomerSendCredentials;
use Shopper\Framework\Repositories\UserRepository;
use Shopper\Framework\Rules\Phone;
use Shopper\Framework\Traits\WithAddress;

class Create extends AbstractBaseComponent
{
    use WithAddress;

    public string $last_name = '';

    public string $first_name = '';

    public string $email = '';

    public string $phone_number = '';

    public bool $opt_in = false;

    public bool $send_mail = false;

    public string $password = '';

    public string $password_confirmation = '';

    public function store(): void
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

    public function generate(): void
    {
        $this->password = mb_substr(mb_strtoupper(uniqid(str_random(10))), 0, 10);
    }

    public function rules(): array
    {
        return array_merge($this->addressRules(), [
            'email' => 'required|max:150|unique:' . shopper_table('users'),
            'first_name' => 'required',
            'last_name' => 'required',
            'country_id' => 'required',
            'street_address' => 'required',
            'password' => 'required|confirmed|min:8',
            'phone_number' => ['nullable', new Phone()],
        ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.customers.create', [
            'countries' => Cache::get('countries-settings', fn () => Country::orderBy('name')->get()),
        ]);
    }
}
