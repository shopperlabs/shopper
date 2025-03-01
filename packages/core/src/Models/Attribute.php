<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as LaravelAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\AttributeFactory;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Traits\HasSlug;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property string | null $description
 * @property FieldType $type
 * @property bool $is_enabled
 * @property bool $is_searchable
 * @property bool $is_filterable
 * @property string|null $icon
 * @property string $type_formatted
 * @property \Illuminate\Database\Eloquent\Collection | AttributeValue[] $values
 */
class Attribute extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_searchable' => 'boolean',
        'is_filterable' => 'boolean',
        'type' => FieldType::class,
    ];

    public function getTable(): string
    {
        return shopper_table('attributes');
    }

    protected static function newFactory(): AttributeFactory
    {
        return AttributeFactory::new();
    }

    protected function typeFormatted(): LaravelAttribute
    {
        return LaravelAttribute::make(
            get: fn (): string => self::typesFields()[$this->type->value]
        );
    }

    public static function typesFields(): array
    {
        return FieldType::options();
    }

    public static function fieldsWithValues(): array
    {
        return [
            FieldType::Checkbox,
            FieldType::ColorPicker,
            FieldType::Select,
        ];
    }

    public function hasMultipleValues(): bool
    {
        return in_array($this->type, [FieldType::Checkbox, FieldType::ColorPicker]);
    }

    public function hasSingleValue(): bool
    {
        return $this->type === FieldType::Select;
    }

    public function hasTextValue(): bool
    {
        return in_array($this->type, [
            FieldType::Text,
            FieldType::Number,
            FieldType::RichText,
            FieldType::DatePicker,
        ]);
    }

    public function updateStatus(bool $status = true): void
    {
        $this->is_enabled = $status;

        $this->save();
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @param  Builder<Attribute>  $query
     * @return Builder<Attribute>
     */
    public function scopeIsFilterable(Builder $query): Builder
    {
        return $query->where('is_filterable', true);
    }

    /**
     * @param  Builder<Attribute>  $query
     * @return Builder<Attribute>
     */
    public function scopeIsSearchable(Builder $query): Builder
    {
        return $query->where('is_searchable', true);
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(config('shopper.models.product'), table: shopper_table('attribute_product'))
            ->withPivot([
                'attribute_value_id',
                'attribute_custom_value',
            ]);
    }
}
