<?php

namespace Shopper\Framework\Http\Livewire\Settings\Inventories;

use Illuminate\Validation\Rule;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\Shop\Inventory\Inventory;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Rules\Phone;

class Edit extends AbstractBaseComponent
{
    /**
     * Current Updated inventory
     *
     * @var Inventory
     */
    public Inventory $inventory;

    /**
     * Inventory id for validation rules.
     *
     * @var int
     */
    public $inventoryId;

    /**
     * Inventory default name.
     *
     * @var string
     */
    public $name;

    /**
     * Inventory description.
     *
     * @var string
     */
    public $description;

    /**
     * Inventory email.
     *
     * @var string
     */
    public $email;

    /**
     * City where a locate th inventory.
     *
     * @var string
     */
    public $city;

    /**
     * Street address to locate inventory on a map.
     *
     * @var string
     */
    public $street_address;

    /**
     * Street address secondary.
     *
     * @var string
     */
    public $street_address_plus;

    /**
     * Zipcode.
     *
     * @var string
     */
    public $zipcode;

    /**
     * Phone number.
     *
     * @var string
     */
    public $phone_number;

    /**
     * Country who inventory is localize.
     *
     * @var integer
     */
    public $country_id;

    /**
     * Define if the inventory is the default.
     *
     * @var bool
     */
    public $isDefault = false;

    /**
     * Component Mount method instance.
     *
     * @param  Inventory  $inventory
     */
    public function mount(Inventory $inventory)
    {
        $this->inventory = $inventory;
        $this->inventoryId = $inventory->id;
        $this->name = $inventory->name;
        $this->email = $inventory->email;
        $this->description = $inventory->description;
        $this->street_address = $inventory->street_address;
        $this->street_address_plus = $inventory->street_address_plus;
        $this->country_id = $inventory->country_id;
        $this->city = $inventory->city;
        $this->zipcode = $inventory->zipcode;
        $this->isDefault = $inventory->is_default;
        $this->phone_number = $inventory->phone_number;
    }

    /**
     * Store/Update a entry to the storage.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        Inventory::query()->find($this->inventoryId)->update([
            'name' => $this->name,
            'email' => $this->email,
            'city' => $this->city,
            'description' => $this->description,
            'street_address' => $this->street_address,
            'street_address_plus' => $this->street_address_plus,
            'zipcode' => $this->zipcode,
            'phone_number' => $this->phone_number,
            'country_id' => $this->country_id,
            'is_default' => $this->isDefault,
        ]);

        session()->flash('success', __('Inventory Successfully updated.'));
        $this->redirectRoute('shopper.settings.inventories.index');
    }

    /**
     * Remove a location to the storage.
     *
     * @throws \Exception
     * @return void
     */
    public function remove()
    {
        Inventory::query()->find($this->inventoryId)->delete();

        session()->flash('success', __('Inventory Successfully removed.'));
        $this->redirectRoute('shopper.settings.inventories.index');
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    protected function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique(shopper_table('inventories'), 'email')->ignore($this->inventoryId)
            ],
            'name' => 'required|max:100',
            'city' => 'required',
            'street_address' => 'required',
            'zipcode'  => 'required',
            'phone_number'  => ['nullable', new Phone()],
            'country_id' => 'required|exists:'.shopper_table('system_countries').',id',
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.inventories.edit', [
            'countries'  => Country::query()->orderBy('name')->get(),
        ]);
    }
}
