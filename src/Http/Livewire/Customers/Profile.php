<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    /**
     * Customer Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $customer;

    /**
     * Customer Id.
     *
     * @var int
     */
    public $customer_id;

    /**
     * Customer First name.
     *
     * @var string
     */
    public $firstName;

    /**
     * Define if the last name can be updated.
     *
     * @var bool
     */
    public $firstNameUpdate = false;

    /**
     * Customer Last name.
     *
     * @var string
     */
    public $lastName;

    /**
     * Define if the last name can be updated.
     *
     * @var bool
     */
    public $lastNameUpdate = false;

    /**
     * Customer Email address.
     *
     * @var string
     */
    public $email;

    /**
     * Define if the email can be updated.
     *
     * @var bool
     */
    public $emailUpdate = false;

    /**
     * Customer gender.
     *
     * @var string
     */
    public $birthDate;

    /**
     * Customer gender.
     *
     * @var string
     */
    public $birthDateFormatted;

    /**
     * Email Marketing Subscribe status.
     *
     * @var bool
     */
    public $optIn;

    /**
     * Define if a user has enabled two factor auth.
     *
     * @var bool
     */
    public $hasEnabledTwoFactor;

    /**
     * Customer gender.
     *
     * @var string
     */
    public $gender;

    /**
     * Define if the gender can be updated.
     *
     * @var bool
     */
    public $genderUpdate = false;

    /**
     * Define if the gender can be updated.
     *
     * @var bool
     */
    public $birthDateUpdate = false;

    /**
     * Component Mount instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $customer
     */
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
        $this->hasEnabledTwoFactor = $customer->two_factor_secret ? true : false;
    }

    /**
     * Update customer first name.
     *
     * @return void
     */
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

    /**
     * Update customer last name.
     *
     * @return void
     */
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

    /**
     * Update Customer Email Address.
     *
     * @return void
     */
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

    /**
     * Cancel Email Address update.
     *
     * @return void
     */
    public function cancelEmail()
    {
        $this->emailUpdate = false;
        $this->email = $this->customer->email;
    }

    /**
     * Update customer Birth Date.
     *
     * @return void
     */
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

    /**
     * Update customer gender.
     *
     * @return void
     */
    public function saveGender()
    {
        $this->updateValue(
            'gender',
            $this->gender,
            'Customer gender updated successfully.'
        );

        $this->genderUpdate = false;
    }

    /**
     * Updated Customer default Email Marketing Subscription.
     *
     * @return void
     */
    public function updatedOptIn()
    {
        $this->updateValue(
            'opt_in',
            $this->optIn,
            "You have updated the customer's marketing email subscription."
        );
    }

    public function render()
    {
        return view('shopper::livewire.customers.profile');
    }

    /**
     * Update value from the storage.
     *
     * @param  string  $field
     * @param  string  $value
     * @param  string  $message
     */
    private function updateValue($field, $value, $message)
    {
        $this->customer->update([$field => $value]);

        $this->notify(['title' => __('Updated'), 'message' => __($message)]);
    }
}
