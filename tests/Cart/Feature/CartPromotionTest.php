<?php

declare(strict_types=1);

use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartPromotion;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Discount;

uses(Tests\Cart\TestCase::class);

describe(CartPromotion::class, function (): void {
    it('casts source to the PromotionSource enum', function (): void {
        $promotion = CartPromotion::factory()->create();

        expect($promotion->source)->toBe(PromotionSource::Code);
    });

    it('creates an automatic promotion without a code', function (): void {
        $promotion = CartPromotion::factory()->automatic()->create();

        expect($promotion->source)->toBe(PromotionSource::Automatic)
            ->and($promotion->code)->toBeNull();
    });

    it('belongs to a cart and a discount', function (): void {
        $cart = Cart::factory()->create();
        $discount = Discount::factory()->create();
        $promotion = CartPromotion::factory()->create([
            'cart_id' => $cart->id,
            'discount_id' => $discount->id,
        ]);

        expect($promotion->cart)->toBeInstanceOf(Cart::class)
            ->and($promotion->cart->id)->toBe($cart->id)
            ->and($promotion->discount)->toBeInstanceOf(Discount::class)
            ->and($promotion->discount->id)->toBe($discount->id);
    });
})->group('cart', 'models');
