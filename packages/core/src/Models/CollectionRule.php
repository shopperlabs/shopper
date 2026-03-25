<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\CollectionRuleFactory;
use Shopper\Core\Enum\Operator;
use Shopper\Core\Enum\Rule;
use Shopper\Core\Models\Contracts\CollectionRule as CollectionRuleContract;

/**
 * @property-read int $id
 * @property-read Rule $rule
 * @property-read Operator $operator
 * @property-read string $value
 * @property-read int $collection_id
 * @property-read Collection $collection
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class CollectionRule extends Model implements CollectionRuleContract
{
    /** @use HasFactory<CollectionRuleFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('collection_rules');
    }

    public function getFormattedRule(): string
    {
        return Rule::options()[$this->rule->value];
    }

    public function getFormattedOperator(): string
    {
        return Operator::options()[$this->operator->value];
    }

    public function getFormattedValue(): string
    {
        if ($this->rule->isPrice()) {
            return shopper_money_format((int) $this->value);
        }

        if ($this->rule->isBoolean()) {
            return $this->value === '1'
                ? __('shopper::forms.label.yes')
                : __('shopper::forms.label.no');
        }

        return $this->value;
    }

    /**
     * @return BelongsTo<Collection, $this>
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.collection'), 'collection_id');
    }

    protected static function newFactory(): CollectionRuleFactory
    {
        return CollectionRuleFactory::new();
    }

    protected function casts(): array
    {
        return [
            'rule' => Rule::class,
            'operator' => Operator::class,
        ];
    }
}
