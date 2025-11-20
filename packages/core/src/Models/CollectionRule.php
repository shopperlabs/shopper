<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\CollectionRuleFactory;
use Shopper\Core\Enum\Operator;
use Shopper\Core\Enum\Rule;

/**
 * @property-read int $id
 * @property-read Rule $rule
 * @property-read Operator $operator
 * @property-read string $value
 * @property-read int $collection_id
 * @property-read Collection $collection
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class CollectionRule extends Model
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
        if ($this->rule === Rule::ProductPrice) {
            return shopper_money_format((int) $this->value);
        }

        return $this->value;
    }

    /**
     * @return BelongsTo<Collection, $this>
     */
    public function collection(): BelongsTo
    {
        // @phpstan-ignore-next-line
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
