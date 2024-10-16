<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as CastAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Traits\HasSlug;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property FieldType $type
 * @property bool $is_enabled
 * @property bool $is_searchable
 * @property bool $is_filterable
 * @property string|null $icon
 * @property string $type_formatted
 * @property \Illuminate\Database\Eloquent\Collection|array $values
 */
class Attribute extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'is_enabled',
        'is_searchable',
        'is_filterable',
        'icon',
    ];

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

    protected function typeFormatted(): CastAttribute
    {
        return CastAttribute::make(
            get: fn () => self::typesFields()[$this->type->value]
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

    public function scopeIsFilterable(Builder $query): Builder
    {
        return $query->where('is_filterable', true);
    }

    public function scopeIsSearchable(Builder $query): Builder
    {
        return $query->where('is_searchable', true);
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
