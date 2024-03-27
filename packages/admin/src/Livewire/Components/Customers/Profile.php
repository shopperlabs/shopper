<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    public $customer;

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

    public function mount($customer): void
    {
        $this->customer = $customer;
        $this->customer_id = $customer->id;
        $this->firstName = $customer->first_name;
        $this->lastName = $customer->last_name;
        $this->email = $customer->email;
        $this->gender = $customer->gender;
        $this->birthDate = $customer->birth_date;
        $this->birthDateFormatted = $customer->birth_date_formatted;
        $this->optIn = (bool) $customer->opt_in;
        $this->hasEnabledTwoFactor = (bool) $customer->two_factor_secret;
    }

    public function saveFirstName(): void
    {
        $this->validate(['firstName' => 'sometimes|required']);

        $this->updateValue(field: 'first_name', value: $this->firstName);

        $this->firstNameUpdate = false;
        $this->emit('profileUpdate');
    }

    public function saveLastName(): void
    {
        $this->validate(['lastName' => 'sometimes|required']);

        $this->updateValue(field: 'last_name', value: $this->lastName);

        $this->lastNameUpdate = false;
        $this->emit('profileUpdate');
    }

    public function saveEmail(): void
    {
        $this->validate([
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique(shopper_table('users'), 'email')->ignore($this->customer_id),
            ],
        ]);

        $this->updateValue(field: 'email', value: $this->email);

        $this->emailUpdate = false;
        $this->emit('profileUpdate');
    }

    public function cancelEmail(): void
    {
        $this->emailUpdate = false;
        $this->email = $this->customer->email;
    }

    public function saveBirthDate(): void
    {
        $this->updateValue(field: 'birth_date', value: $this->birthDate);

        $this->birthDateUpdate = false;
        $this->birthDateFormatted = $this->customer->birth_date_formatted;
    }

    public function saveGender(): void
    {
        $this->updateValue(field: 'gender', value: $this->gender);

        $this->genderUpdate = false;
    }

    public function updatedOptIn(): void
    {
        $this->updateValue(field: 'opt_in', value: $this->optIn);
    }

    public function render(): View
    {
        return view('shopper::livewire.customers.profile');
    }

    private function updateValue(string $field, mixed $value): void
    {
        $this->customer->update([$field => $value]);

        Notification::make()
            ->body(__('shopper::notifications.actions.update', ['item' => __('shopper::words.customer')]))
            ->success()
            ->send();
    }
}
