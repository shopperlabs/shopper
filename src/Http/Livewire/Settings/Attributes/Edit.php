<?php

namespace Shopper\Framework\Http\Livewire\Settings\Attributes;

use function in_array;
use Illuminate\Validation\Rule;
use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;

class Edit extends AbstractBaseComponent
{
    /**
     * Current updated attribute.
     */
    public Attribute $attribute;

    /**
     * Attribute id for validation.
     *
     * @var int
     */
    public $attributeId;

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
     * Attribute type.
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
     * Component Mount new instance.
     */
    public function mount(Attribute $attribute)
    {
        $this->attribute = $attribute;
        $this->attributeId = $attribute->id;
        $this->name = $attribute->name;
        $this->slug = $attribute->slug;
        $this->type = $attribute->type;
        $this->description = $attribute->description;
        $this->isEnabled = $attribute->is_enabled;
        $this->isSearchable = $attribute->is_searchable;
        $this->isFilterable = $attribute->is_filterable;
    }

    /**
     * Store/Update a entry to the storage.
     */
    public function store()
    {
        $this->validate($this->rules());

        Attribute::query()->find($this->attributeId)->update([
            'name' => $this->name,
            'slug' => str_slug($this->slug, '-'),
            'type' => $this->type,
            'description' => $this->description,
            'is_enabled' => $this->isEnabled,
            'is_searchable' => $this->isSearchable,
            'is_filterable' => $this->isFilterable,
        ]);

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Attribute has been successfully updated!'),
        ]);
    }

    /**
     * Check if the attribute has default values.
     */
    public function hasValues(): bool
    {
        return in_array($this->type, Attribute::fieldsWithValues());
    }

    public function render()
    {
        return view('shopper::livewire.settings.attributes.edit', [
            'fields' => Attribute::typesFields(),
        ]);
    }

    /**
     * Component validation rules.
     *
     * @return array<string>
     */
    protected function rules()
    {
        return [
            'name' => 'required|max:75',
            'slug' => [
                'required',
                Rule::unique(shopper_table('attributes'), 'slug')->ignore($this->attributeId),
            ],
            'type' => 'required',
        ];
    }
}
