<?php

declare(strict_types=1);

use Shopper\Cart\Contracts\DiscountEligibilityRule;
use Shopper\Cart\Discounts\DiscountEligibilityManager;
use Shopper\Cart\Discounts\DiscountValidationResult;
use Shopper\Cart\Discounts\SyncDiscountEligibilityAction;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;

uses(Tests\Cart\TestCase::class);

function syncDiscount(): Discount
{
    return Discount::factory()->create([
        'code' => 'SYNC',
        'is_active' => true,
        'type' => DiscountType::Percentage,
        'value' => 10,
        'apply_to' => DiscountApplyTo::Order,
        'eligibility' => 'group',
        'min_required' => DiscountRequirement::None,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
    ]);
}

function registerGroupMode(string $type = 'customer_group'): void
{
    resolve(DiscountEligibilityManager::class)->register(new class($type) implements DiscountEligibilityRule
    {
        public function __construct(private string $type) {}

        public function key(): string
        {
            return 'group';
        }

        public function label(): string
        {
            return 'Groups';
        }

        public function description(): string
        {
            return 'Groups only.';
        }

        public function discountableType(): ?string
        {
            return $this->type;
        }

        public function passes(Discount $discount, CartPipelineContext $context): DiscountValidationResult
        {
            return new DiscountValidationResult(true);
        }
    });
}

describe(SyncDiscountEligibilityAction::class, function (): void {
    it('preserves the per-target redemption counter when the set is resaved', function (): void {
        registerGroupMode();
        $discount = syncDiscount();

        DiscountDetail::query()->create([
            'discount_id' => $discount->id,
            'discountable_type' => 'customer_group',
            'discountable_id' => 7,
            'condition' => DiscountCondition::Eligibility,
            'total_use' => 3,
        ]);

        resolve(SyncDiscountEligibilityAction::class)->execute($discount, 'group', [7, 9]);

        $kept = DiscountDetail::query()
            ->where('discount_id', $discount->id)
            ->where('discountable_id', 7)
            ->sole();

        expect($kept->total_use)->toBe(3)
            ->and(DiscountDetail::query()->where('discount_id', $discount->id)->count())->toBe(2);
    });

    it('never touches ApplyTo rows nor another mode type it does not own', function (): void {
        registerGroupMode();
        $discount = syncDiscount();

        $productRow = DiscountDetail::query()->create([
            'discount_id' => $discount->id,
            'discountable_type' => 'product',
            'discountable_id' => 5,
            'condition' => DiscountCondition::ApplyTo,
            'total_use' => 0,
        ]);

        $collidingUserRow = DiscountDetail::query()->create([
            'discount_id' => $discount->id,
            'discountable_type' => config('auth.providers.users.model'),
            'discountable_id' => 9,
            'condition' => DiscountCondition::Eligibility,
            'total_use' => 0,
        ]);

        resolve(SyncDiscountEligibilityAction::class)->execute($discount, 'group', [9]);

        expect(DiscountDetail::query()->whereKey($productRow->id)->exists())->toBeTrue()
            ->and(DiscountDetail::query()->whereKey($collidingUserRow->id)->exists())->toBeFalse()
            ->and(DiscountDetail::query()
                ->where('discountable_type', 'customer_group')
                ->where('discountable_id', 9)
                ->exists())->toBeTrue();
    });

    it('clears every eligibility row when the mode owns no type', function (): void {
        $discount = syncDiscount();

        DiscountDetail::query()->create([
            'discount_id' => $discount->id,
            'discountable_type' => config('auth.providers.users.model'),
            'discountable_id' => 1,
            'condition' => DiscountCondition::Eligibility,
            'total_use' => 0,
        ]);

        resolve(SyncDiscountEligibilityAction::class)->execute($discount, 'everyone', []);

        expect($discount->items()->where('condition', DiscountCondition::Eligibility)->count())->toBe(0);
    });
})->group('cart', 'discounts');
