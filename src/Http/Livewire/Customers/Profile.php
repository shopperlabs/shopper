<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Livewire\Component;
use WireUi\Traits\Actions;

class Profile extends Component
{
    use Actions;

    public Model $customer;

    public int $customer_id;

    public string $firstName;

    public string $lastName;

    public string $email;

    public ?string $birthDate = null;

    public string $birthDateFormatted = '';

    public string $gender;

    public bool $firstNameUpdate = false;

    public bool $lastNameUpdate = false;

    public bool $emailUpdate = false;

    public bool $genderUpdate = false;

    public bool $birthDateUpdate = false;

    public bool $optIn;

    public bool $hasEnabledTwoFactor;

    public function mount($customer)
    {
        $this->customer = $customer;
        $this->customer_id = $customer->id;
        $this->firstName = $customer->first_name;
        $this->lastName = $customer->last_name;
        $this->email = $customer->email;
        $this->gender = $customer->gender;
        $this->birthDate = $customer->birth_date;
        $this->birthDateFormatted = $customer->birth_date_formatted;
        $this->optIn = $customer->opt_in;
        $this->hasEnabledTwoFactor = (bool) $customer->two_factor_secret;
    }

    public function saveFirstName()
    {
        $this->validate(['firstName' => 'sometimes|required']);

        $this->updateValue(
            'first_name',
            $this->firstName,
            'Customer First name updated successfully.'
        );

        $this->firstNameUpdate = false;
        $this->emit('profileUpdate');
    }

    public function saveLastName()
    {
        $this->validate(['lastName' => 'sometimes|required']);

        $this->updateValue(
            'last_name',
            $this->lastName,
            'Customer Last name updated successfully.'
        );

        $this->lastNameUpdate = false;
        $this->emit('profileUpdate');
    }

    public function saveEmail()
    {
        $this->validate([
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique(shopper_table('users'), 'email')->ignore($this->customer_id),
            ],
        ]);

        $this->updateValue(
            'email',
            $this->email,
            'Customer Email address updated successfully.'
        );

        $this->emailUpdate = false;
        $this->emit('profileUpdate');
    }

    public function cancelEmail()
    {
        $this->emailUpdate = false;
        $this->email = $this->customer->email;
    }

    public function saveBirthDate()
    {
        $this->updateValue(
            'birth_date',
            $this->birthDate,
            'Customer birth date updated successfully.'
        );

        $this->birthDateUpdate = false;
        $this->birthDateFormatted = $this->customer->birth_date_formatted;
    }

    public function saveGender()
    {
        $this->updateValue(
            'gender',
            $this->gender,
            'Customer gender updated successfully.'
        );

        $this->genderUpdate = false;
    }

    public function updatedOptIn()
    {
        $this->updateValue(
            'opt_in',
            $this->optIn,
            "You have updated the customer's marketing email subscription."
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.customers.profile');
    }

    /**
     * Update value from the storage.
     *
     * @param  string  $field
     * @param  mixed  $value
     * @param  string  $message
     */
    private function updateValue(string $field, mixed $value, string $message)
    {
        $this->customer->update([$field => $value]);

        $this->notification()->success(__('shopper::layout.status.updated'), __($message));
    }
}
