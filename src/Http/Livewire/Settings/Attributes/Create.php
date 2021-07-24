<?php

namespace Shopper\Framework\Http\Livewire\Settings\Attributes;

use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;

class Create extends AbstractBaseComponent
{
    /**
     * Attribute name.
     *
     * @var string
     */
    public $name;

    /**
     * Attribute slug code.
     *
     * @var string
     */
    public $slug;

    /**
     * Attribute product type.
     *
     * @var string
     */
    public $type = 'text';

    /**
     * Attribute description.
     *
     * @var string
     */
    public $description;

    /**
     * Define if the attribute is enabled for the client side.
     *
     * @var bool
     */
    public $isEnabled = false;

    /**
     * Define if the attribute can be searchable.
     *
     * @var bool
     */
    public $isSearchable = false;

    /**
     * Define if the attribute can be filterable.
     *
     * @var bool
     */
    public $isFilterable = false;

    /**
     * Update slug value when writing name.
     */
    public function updatedName(string $value)
    {
        $this->slug = str_slug($value);
    }

    /**
     * Store/Update a entry to the storage.
     */
    public function store()
    {
        $this->validate($this->rules());

        $attribute = Attribute::query()->create([
            'name' => $this->name,
            'slug' => str_slug($this->slug, '-'),
            'type' => $this->type,
            'description' => $this->description,
            'is_enabled' => $this->isEnabled,
            'is_searchable' => $this->isSearchable,
            'is_filterable' => $this->isFilterable,
        ]);

        session()->flash('success', __('Attribute successfully added'));
        $this->redirectRoute('shopper.settings.attributes.edit', $attribute);
    }

    public function render()
    {
        return view('shopper::livewire.settings.attributes.create', [
            'fields' => Attribute::typesFields(),
        ]);
    }

    /**
     * Component validation rules.
     *
     * @return array<string>
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|max:75',
            'slug' => 'required|unique:' . shopper_table('attributes'),
            'type' => 'required',
        ];
    }
}
