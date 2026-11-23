<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLineAdjustment;
use Shopper\Cart\Models\CartLineTaxLine;
use Shopper\Cart\Models\CartShippingTaxLine;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxZone;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->cartManager = resolve(CartManager::class);

    $currency = Currency::query()->where('code', 'USD')->first();
    $inventory = Inventory::factory()->create();

    $product = Product::factory()->standard()->create();
    $product->prices()->create(['amount' => 10000, 'currency_id' => $currency->id]);
    $product->load('prices');
    $product->mutateStock($inventory->id, 100);

    $country = Country::query()->where('cca2', 'US')->first()
        ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

    $this->taxRate = TaxRate::factory()->create([
        'tax_zone_id' => TaxZone::factory()->create(['country_id' => $country->id, 'is_tax_inclusive' => false])->id,
        'rate' => 20.00,
        'is_default' => true,
    ]);

    [$this->cart, $this->otherCart] = collect([1, 2])->map(function () use ($product, $country): Cart {
        $cart = Cart::factory()->create(['currency_code' => 'USD']);

        $this->cartManager->add($cart, $product);
        $this->cartManager->addAddress($cart, AddressType::Shipping, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
            'country_id' => $country->id,
        ]);

        return $cart;
    })->all();

    DB::commit();
    RefreshDatabaseState::$migrated = false;

    config(['database.connections.competitor' => Arr::except(DB::connection()->getConfig(), 'name')]);
});

describe('Concurrent cart calculations', function (): void {
    it('holds the cart row lock while the pipeline rewrites the cart rows', function (): void {
        $locked = false;

        config()->push('shopper.cart.pipelines.cart', function (CartPipelineContext $context, Closure $next) use (&$locked): mixed {
            try {
                DB::connection('competitor')->transaction(fn () => DB::connection('competitor')
                    ->table($context->cart->getTable())
                    ->where('id', $context->cart->getKey())
                    ->lock('for update nowait')
                    ->first());
            } catch (QueryException $exception) {
                throw_unless($exception->getCode() === '55P03' || in_array($exception->errorInfo[1] ?? null, [3572, 1205], true), $exception);

                $locked = true;
            }

            return $next($context);
        });

        $this->cartManager->calculate($this->cart);

        expect($locked)->toBeTrue();
    });

    it('lets another cart write its tax and discount rows while a calculation is open', function (): void {
        $waited = false;
        $otherLineId = $this->otherCart->lines()->value('id');

        Discount::factory()->create([
            'code' => 'SAVE10',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'start_at' => now()->subDay(),
        ]);

        $this->cartManager->applyCoupon($this->cart, 'SAVE10');

        config()->push('shopper.cart.pipelines.cart', function (CartPipelineContext $context, Closure $next) use (&$waited, $otherLineId): mixed {
            $competitor = DB::connection('competitor');
            $competitor->statement($competitor->getDriverName() === 'pgsql' ? "set lock_timeout = '1s'" : 'set innodb_lock_wait_timeout = 1');

            try {
                $competitor->transaction(function () use ($competitor, $otherLineId): void {
                    $competitor->table((new CartLineTaxLine)->getTable())->insert([
                        'cart_line_id' => $otherLineId,
                        'tax_rate_id' => $this->taxRate->id,
                        'code' => 'VAT',
                        'name' => 'VAT',
                        'rate' => 20,
                        'amount' => 2000,
                    ]);

                    $competitor->table((new CartLineAdjustment)->getTable())->insert([
                        'cart_line_id' => $otherLineId,
                        'amount' => 500,
                    ]);

                    $competitor->table((new CartShippingTaxLine)->getTable())->insert([
                        'cart_id' => $this->otherCart->id,
                        'tax_rate_id' => $this->taxRate->id,
                        'code' => 'VAT',
                        'name' => 'VAT',
                        'rate' => 20,
                        'amount' => 200,
                    ]);
                });
            } catch (QueryException $exception) {
                throw_unless($exception->getCode() === '55P03' || ($exception->errorInfo[1] ?? null) === 1205, $exception);

                $waited = true;
            }

            return $next($context);
        });

        $this->cartManager->calculate($this->cart);

        expect($waited)->toBeFalse();
    });

    it('lets a customer remove a coupon while another cart rewrites its discount rows', function (): void {
        $waited = false;

        Discount::factory()->create([
            'code' => 'SAVE10',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'start_at' => now()->subDay(),
        ]);

        foreach ([$this->cart, $this->otherCart] as $cart) {
            $this->cartManager->applyCoupon($cart, 'SAVE10');
            $this->cartManager->calculate($cart);
        }

        $competitor = DB::connection('competitor');
        $competitor->beginTransaction();
        $competitor->table((new CartLineAdjustment)->getTable())
            ->whereIn('id', CartLineAdjustment::query()->whereIn('cart_line_id', $this->otherCart->lines()->select('id'))->pluck('id'))
            ->delete();

        DB::statement(DB::getDriverName() === 'pgsql' ? "set lock_timeout = '1s'" : 'set innodb_lock_wait_timeout = 1');

        try {
            $this->cartManager->removeCoupon($this->cart, 'SAVE10');
        } catch (QueryException $exception) {
            throw_unless($exception->getCode() === '55P03' || ($exception->errorInfo[1] ?? null) === 1205, $exception);

            $waited = true;
        } finally {
            $competitor->rollBack();
        }

        expect($waited)->toBeFalse();
    });

    it('removes a coupon only once the calculation holding the cart has finished', function (): void {
        Discount::factory()->create([
            'code' => 'SAVE10',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'start_at' => now()->subDay(),
        ]);

        $this->cartManager->applyCoupon($this->cart, 'SAVE10');
        $this->cartManager->calculate($this->cart);

        $competitor = DB::connection('competitor');
        $competitor->beginTransaction();
        $competitor->table($this->cart->getTable())->where('id', $this->cart->getKey())->lockForUpdate()->first();

        DB::statement(DB::getDriverName() === 'pgsql' ? "set lock_timeout = '1s'" : 'set innodb_lock_wait_timeout = 1');

        try {
            expect(fn () => $this->cartManager->removeCoupon($this->cart, 'SAVE10'))->toThrow(QueryException::class);
        } finally {
            $competitor->rollBack();
        }

        expect($this->cart->promotions()->count())->toBe(1);
    });
})
    ->skip(fn (): bool => DB::getDriverName() === 'sqlite', 'Needs two connections on the same database.')
    ->group('cart', 'cart-calculation');
