<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLineAdjustment;
use Shopper\Cart\Models\CartLineTaxLine;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Zone;

uses(Tests\Upgrade\TestCase::class);

beforeEach(function (): void {
    setupCurrencies(['USD', 'XAF']);

    $this->xaf = Currency::query()->where('code', 'XAF')->first()
        ?? Currency::query()->create(['name' => 'Central African CFA Franc', 'code' => 'XAF', 'symbol' => 'FCFA', 'format' => '1,0 FCFA']);

    $this->usd = Currency::query()->where('code', 'USD')->first();
});

describe('shopper:fix-zero-decimal-currencies', function (): void {
    it('skips when no zero-decimal currencies exist in database', function (): void {
        DB::table(shopper_table('currencies'))->whereIn('code', zero_decimal_currencies())->delete();

        $this->artisan('shopper:fix-zero-decimal-currencies', ['--force' => true])
            ->assertSuccessful();
    });

    it('does not modify data with `--dry-run`', function (): void {
        $product = Product::factory()->create();
        Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $this->xaf->id,
            'amount' => 60000,
        ]);

        $this->artisan('shopper:fix-zero-decimal-currencies', ['--dry-run' => true])
            ->assertSuccessful();

        expect(DB::table(shopper_table('prices'))->where('currency_id', $this->xaf->id)->value('amount'))
            ->toBe(60000);
    });

    it('divides `prices` amounts by 100 for zero-decimal currencies', function (): void {
        $product = Product::factory()->create();
        Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $this->xaf->id,
            'amount' => 60000,
            'compare_amount' => 80000,
            'cost_amount' => 30000,
        ]);

        $this->artisan('shopper:fix-zero-decimal-currencies', ['--force' => true])
            ->assertSuccessful();

        $price = DB::table(shopper_table('prices'))->where('currency_id', $this->xaf->id)->first();

        expect($price->amount)->toBe(600)
            ->and($price->compare_amount)->toBe(800)
            ->and($price->cost_amount)->toBe(300);
    });

    it('divides `orders` and `order_items` amounts by 100 for zero-decimal currencies', function (): void {
        $order = Order::factory()->create([
            'currency_code' => 'XAF',
            'price_amount' => 60000,
            'tax_amount' => 5000,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'unit_price_amount' => 60000,
            'tax_amount' => 5000,
            'discount_amount' => 1000,
        ]);

        $this->artisan('shopper:fix-zero-decimal-currencies', ['--force' => true])
            ->assertSuccessful();

        $fixedOrder = DB::table(shopper_table('orders'))->find($order->id);

        expect($fixedOrder->price_amount)->toBe(600)
            ->and($fixedOrder->tax_amount)->toBe(50);

        $fixedItem = DB::table(shopper_table('order_items'))->where('order_id', $order->id)->first();

        expect($fixedItem->unit_price_amount)->toBe(600)
            ->and($fixedItem->tax_amount)->toBe(50)
            ->and($fixedItem->discount_amount)->toBe(10);
    });

    it('divides `carrier_options` price by 100 for zones with zero-decimal currency', function (): void {
        $zone = Zone::factory()->create(['currency_id' => $this->xaf->id]);
        $carrier = Carrier::factory()->create();

        CarrierOption::factory()->create([
            'zone_id' => $zone->id,
            'carrier_id' => $carrier->id,
            'price' => 60000,
        ]);

        $this->artisan('shopper:fix-zero-decimal-currencies', ['--force' => true])
            ->assertSuccessful();

        expect(DB::table(shopper_table('carrier_options'))->where('zone_id', $zone->id)->value('price'))
            ->toBe(600);
    });

    it('divides cart line amounts by 100 for carts with zero-decimal currency', function (): void {
        $product = Product::factory()->create();

        $cart = Cart::query()->create(['currency_code' => 'XAF']);

        $line = $cart->lines()->create([
            'purchasable_type' => $product->getMorphClass(),
            'purchasable_id' => $product->id,
            'quantity' => 1,
            'unit_price_amount' => 60000,
        ]);

        CartLineTaxLine::query()->create([
            'cart_line_id' => $line->id,
            'code' => 'VAT',
            'name' => 'VAT 19.25%',
            'rate' => 19.25,
            'amount' => 11550,
        ]);

        CartLineAdjustment::query()->create([
            'cart_line_id' => $line->id,
            'amount' => 5000,
            'code' => 'PROMO',
        ]);

        $this->artisan('shopper:fix-zero-decimal-currencies', ['--force' => true])
            ->assertSuccessful();

        expect(DB::table(shopper_table('cart_lines'))->find($line->id)->unit_price_amount)->toBe(600)
            ->and(DB::table(shopper_table('cart_line_tax_lines'))->where('cart_line_id', $line->id)->value('amount'))->toBe(115)
            ->and(DB::table(shopper_table('cart_line_adjustments'))->where('cart_line_id', $line->id)->value('amount'))->toBe(50);
    });

    it('divides fixed amount `discounts` value by 100 for zones with zero-decimal currency', function (): void {
        $zone = Zone::factory()->create(['currency_id' => $this->xaf->id]);

        Discount::factory()->create([
            'type' => 'fixed_amount',
            'value' => 500000,
            'zone_id' => $zone->id,
        ]);

        $this->artisan('shopper:fix-zero-decimal-currencies', ['--force' => true])
            ->assertSuccessful();

        expect(DB::table(shopper_table('discounts'))->where('zone_id', $zone->id)->value('value'))
            ->toBe(5000);
    });

    it('does not touch percentage discounts', function (): void {
        $zone = Zone::factory()->create(['currency_id' => $this->xaf->id]);

        Discount::factory()->create([
            'type' => 'percentage',
            'value' => 20,
            'zone_id' => $zone->id,
        ]);

        $this->artisan('shopper:fix-zero-decimal-currencies', ['--force' => true])
            ->assertSuccessful();

        expect(DB::table(shopper_table('discounts'))->where('zone_id', $zone->id)->value('value'))
            ->toBe(20);
    });

    it('does not modify USD prices (standard subdivision currency)', function (): void {
        $product = Product::factory()->create();

        Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $this->usd->id,
            'amount' => 16300,
        ]);

        $order = Order::factory()->create([
            'currency_code' => 'USD',
            'price_amount' => 16300,
        ]);

        $this->artisan('shopper:fix-zero-decimal-currencies', ['--force' => true])
            ->assertSuccessful();

        expect(DB::table(shopper_table('prices'))->where('currency_id', $this->usd->id)->value('amount'))
            ->toBe(16300)
            ->and(DB::table(shopper_table('orders'))->find($order->id)->price_amount)
            ->toBe(16300);
    });

    it('cancels when user refuses confirmation', function (): void {
        $product = Product::factory()->create();
        Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $this->xaf->id,
            'amount' => 60000,
        ]);

        $this->artisan('shopper:fix-zero-decimal-currencies')
            ->expectsConfirmation('Apply these fixes to your database?', 'no')
            ->assertSuccessful();

        expect(DB::table(shopper_table('prices'))->where('currency_id', $this->xaf->id)->value('amount'))
            ->toBe(60000);
    });
})
    ->group('upgrade');
