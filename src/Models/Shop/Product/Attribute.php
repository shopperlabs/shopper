<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Framework\Models\Traits\HasSlug;

class Attribute extends Model
{
    use HasFactory;
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'is_enabled',
        'is_searchable',
        'is_filterable',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'is_searchable' => 'boolean',
        'is_filterable' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'type_formatted',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('attributes');
    }

    /**
     * Return formatted type.
     */
    public function getTypeFormattedAttribute(): string
    {
        return self::typesFields()[$this->type];
    }

    /**
     * Return available fields types.
     *
     * @return array<string>
     */
    public static function typesFields(): array
    {
        return [
            'text' => __('Text field :type', ['type' => '(input)']),
            'number' => __('Text field :type', ['type' => '(number)']),
            'richtext' => __('Richtext'),
            'select' => __('Select'),
            'checkbox' => __('Checkbox'),
            'radio' => __('Radio'),
            'colorpicker' => __('Color picker'),
            'datepicker' => __('Date picker'),
        ];
    }

    /**
     * Return attributes fields that has values by default.
     *
     * @return array<string>
     */
    public static function fieldsWithValues(): array
    {
        return [
            'select',
            'checkbox',
            'colorpicker',
            'radio',
        ];
    }

    /**
     * Return attributes fields that has custom string values.
     *
     * @return array<string>
     */
    public static function fieldsWithStringValues(): array
    {
        return [
            'text',
            'number',
            'richtext',
            'datepicker',
        ];
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
