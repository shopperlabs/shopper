<?php

namespace Shopper\Framework\Http\Livewire\Settings\Inventories;

use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\Shop\Inventory\Inventory;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Rules\Phone;

class Create extends AbstractBaseComponent
{
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
     * Store/Update a entry to the storage.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        Inventory::query()->create([
            'name' => $this->name,
            'code' => str_slug($this->name),
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

        session()->flash('success', __('Inventory Successfully Added.'));
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
            'email' => 'required|email|unique:'.shopper_table('inventories'),
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
        return view('shopper::livewire.settings.inventories.create', [
            'countries'  => Country::query()->orderBy('name')->get(),
        ]);
    }
}
