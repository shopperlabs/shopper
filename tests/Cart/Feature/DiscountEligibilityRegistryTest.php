<?php

declare(strict_types=1);

use Shopper\Cart\Contracts\DiscountEligibilityRule;
use Shopper\Cart\Discounts\DiscountEligibilityManager;
use Shopper\Cart\Discounts\DiscountValidationResult;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Discount;
use Tests\Core\Stubs\User;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->user = User::factory()->create();
    $this->cart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $this->user->id]);
    $this->context = new CartPipelineContext($this->cart);
    $this->context->subtotal = 5000;
});

function eligibilityDiscount(string $eligibility): Discount
{
    return Discount::factory()->create([
        'code' => 'ELIG',
        'is_active' => true,
        'type' => DiscountType::Percentage,
        'value' => 10,
        'apply_to' => DiscountApplyTo::Order,
        'eligibility' => $eligibility,
        'min_required' => DiscountRequirement::None,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
    ]);
}

describe('Discount eligibility registry', function (): void {
    it('ships the built-in everyone and customers rules', function (): void {
        $manager = resolve(DiscountEligibilityManager::class);

        expect($manager->for('everyone'))->not->toBeNull()
            ->and($manager->for('customers'))->not->toBeNull()
            ->and(array_keys($manager->options()))->toBe(['everyone', 'customers']);
    });

    it('lets an addon register a custom eligibility mode the validator honours', function (): void {
        $rule = new class implements DiscountEligibilityRule
        {
            public function key(): string
            {
                return 'vip';
            }

            public function label(): string
            {
                return 'VIP only';
            }

            public function description(): string
            {
                return 'Only VIP carts qualify.';
            }

            public function discountableType(): ?string
            {
                return null;
            }

            public function passes(Discount $discount, CartPipelineContext $context): DiscountValidationResult
            {
                return new DiscountValidationResult(
                    $context->cart->getAttribute('is_vip') === true,
                    'Not a VIP cart.',
                );
            }
        };

        resolve(DiscountEligibilityManager::class)->register($rule);

        $discount = eligibilityDiscount('vip');
        $validator = resolve(DiscountValidator::class);

        expect($validator->validate($discount, $this->context)->valid)->toBeFalse();

        $this->cart->setAttribute('is_vip', true);
        $context = new CartPipelineContext($this->cart);
        $context->subtotal = 5000;

        expect($validator->validate($discount, $context)->valid)->toBeTrue();
    });

    it('treats an unknown eligibility mode as unrestricted', function (): void {
        $discount = eligibilityDiscount('mode-with-no-rule');

        expect(resolve(DiscountValidator::class)->validate($discount, $this->context)->valid)->toBeTrue();
    });
})->group('cart', 'discounts');
