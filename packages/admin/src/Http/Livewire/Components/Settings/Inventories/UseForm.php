<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Settings\Inventories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Shopper\Core\Rules\Phone;

trait UseForm
{
    public ?int $inventoryId = null;

    public string $name = '';

    public ?string $description = null;

    public string $email = '';

    public string $city = '';

    public string $street_address = '';

    public ?string $street_address_plus = null;

    public string $zipcode = '';

    public ?string $phone_number = null;

    public ?int $country_id = null;

    public bool $isDefault = false;

    public function rules(): array
    {
        return [
            'email' => array_merge([
                'required',
                'email',
            ], $this->inventoryId ? [Rule::unique(shopper_table('inventories'), 'email')->ignore($this->inventoryId)] : []),
            'name' => 'required|max:100',
            'city' => 'required',
            'street_address' => 'required',
            'zipcode' => 'required',
            'phone_number' => ['nullable', new Phone()],
            'country_id' => 'required|exists:' . shopper_table('system_countries') . ',id',
        ];
    }

    public function save(Model|string $model): mixed
    {
        $this->validate($this->rules());

        $values = [
            'name' => $this->name,
            'code' => $model->code ?? str_slug($this->name),
            'email' => $this->email,
            'city' => $this->city,
            'description' => $this->description,
            'street_address' => $this->street_address,
            'street_address_plus' => $this->street_address_plus,
            'zipcode' => $this->zipcode,
            'phone_number' => $this->phone_number,
            'country_id' => $this->country_id,
            'is_default' => $this->isDefault,
        ];

        return $this->inventoryId ? $model->update($values) : $model::query()->create($values);
    }
}
