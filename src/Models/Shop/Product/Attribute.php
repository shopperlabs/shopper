<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
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
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('attributes');
    }

    /**
     * Return formatted type.
     *
     * @return string
     */
    public function getTypeFormattedAttribute()
    {
        return self::typesFields()[$this->type];
    }

    /**
     * Return available fields types.
     *
     * @return string[]
     */
    public static function typesFields()
    {
        return [
          'text' => __('Text field :type', ['type' => '(input)']),
          'number' => __('Text field :type', ['type' => '(number)']) ,
          'richtext' => __('Richtext') ,
          'markdown' => __('Markdown') ,
          'select' => __('Select') ,
          'checkbox' => __('Checkbox') ,
          'checkbox_list' => __('Checkbox List') ,
          'radio' => __('Radio') ,
          // 'toggle' => __('Toggle') ,
          'colorpicker' => __('Color picker') ,
          'datepicker' => __('Date picker') ,
          // 'file' => __('File') ,
        ];
    }

    /**
     * Return attributes fields that has values by default.
     *
     * @return string[]
     */
    public static function fieldsWithValues()
    {
        return [
            'select',
            'checkbox',
            'checkbox_list',
            'colorpicker',
            'radio',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
