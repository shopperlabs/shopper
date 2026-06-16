<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use Closure;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Discounts\PromotionResolver;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Discount;

final readonly class ApplyAutomaticPromotions
{
    public function __construct(
        private DiscountValidator $validator,
        private PromotionResolver $resolver,
    ) {}

    /**
     * Materialise or drop a cart_promotions row for each automatic discount,
     * re-evaluated on every recalculation so eligibility tracks the cart.
     */
    public function handle(CartPipelineContext $context, Closure $next): mixed
    {
        $this->sync($context);

        return $next($context);
    }

    private function sync(CartPipelineContext $context): void
    {
        $cart = $context->cart;

        $automatic = Discount::query()
            ->where('trigger', PromotionSource::Automatic->value)
            ->active()
            ->with('campaign', 'items', 'zone')
            ->get();

        $existing = $cart->promotions
            ->where('source', PromotionSource::Automatic)
            ->keyBy('discount_id');

        $changed = false;

        foreach ($automatic as $discount) {
            $eligible = $this->validator->validate($discount, $context)->valid
                && $this->resolver->wouldApply($discount, $context);
            $present = $existing->has($discount->id);

            if ($eligible && ! $present) {
                // Race-safe against a concurrent recalculation also materialising it.
                $cart->promotions()->firstOrCreate(
                    ['discount_id' => $discount->id],
                    [
                        'source' => PromotionSource::Automatic->value,
                        'code' => null,
                    ],
                );
                $changed = true;
            } elseif (! $eligible && $present) {
                $existing->get($discount->id)->delete();
                $changed = true;
            }
        }

        // Drop automatic rows whose discount is no longer automatic or active.
        foreach ($existing as $discountId => $promotion) {
            if (! $automatic->contains('id', $discountId)) {
                $promotion->delete();
                $changed = true;
            }
        }

        if ($changed) {
            $cart->load('promotions.discount.campaign', 'promotions.discount.items');
        }
    }
}
