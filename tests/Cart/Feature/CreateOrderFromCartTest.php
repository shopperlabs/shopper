<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Cart\Actions\CreateOrderFromCartAction;
use Shopper\Cart\CartManager;
use Shopper\Cart\Events\CartCompleted;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\PriceChangedException;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\CampaignBudgetMovement;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderAddress;
use Shopper\Core\Models\OrderTaxLine;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxZone;
use Tests\Core\Stubs\User;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->user = User::factory()->create();
    $this->cartManager = resolve(CartManager::class);
    $this->action = resolve(CreateOrderFromCartAction::class);
    $this->inventory = Inventory::factory()->create();

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create([
        'amount' => 25,
        'currency_id' => $this->currency->id,
    ]);
    $this->product->load('prices');
    $this->product->mutateStock($this->inventory->id, 100);

    $this->cart = Cart::factory()->create([
        'currency_code' => 'USD',
        'customer_id' => $this->user->id,
    ]);
});

describe(CreateOrderFromCartAction::class, function (): void {
    it('creates an order from a cart with lines', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $order = $this->action->execute($this->cart);

        expect($order)->toBeInstanceOf(Order::class)
            ->and($order->currency_code)->toBe('USD')
            ->and($order->customer_id)->toBe($this->user->id)
            ->and($order->items)->toHaveCount(1)
            ->and($order->items->first()->quantity)->toBe(2);
    });

    it('transfers discount amount to order items', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $line = $this->cart->lines->first();
        $line->adjustments()->create([
            'amount' => 5,
            'code' => 'TEST',
            'discount_id' => null,
        ]);

        $order = $this->action->execute($this->cart->refresh());
        $orderItem = $order->items->first();

        expect($orderItem->discount_amount)->toBe(5);
    });

    it('creates order addresses from cart addresses', function (): void {
        $country = Country::query()->where('cca2', 'US')->first()
            ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
            'country_id' => $country->id,
        ]);

        $order = $this->action->execute($this->cart->refresh());

        expect($order->shippingAddress)->toBeInstanceOf(OrderAddress::class)
            ->and($order->shippingAddress->first_name)->toBe('John')
            ->and($order->shippingAddress->city)->toBe('New York')
            ->and($order->shippingAddress->country_name)->toBe('United States');
    });

    it('freezes the cart tax on the order so a discounted invoice always reconciles', function (): void {
        $country = Country::query()->where('cca2', 'US')->first()
            ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

        $taxZone = TaxZone::factory()->create([
            'country_id' => $country->id,
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'tax_zone_id' => $taxZone->id,
            'rate' => 20.00,
            'is_default' => true,
        ]);

        $product = Product::factory()->standard()->create();
        $product->prices()->create([
            'amount' => 10000,
            'currency_id' => $this->currency->id,
        ]);
        $product->load('prices');
        $product->mutateStock($this->inventory->id, 100);

        Discount::factory()->create([
            'code' => 'TEN',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonth(),
        ]);

        $this->cartManager->add($this->cart, $product);
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
            'country_id' => $country->id,
        ]);
        $this->cartManager->applyCoupon($this->cart, 'TEN');

        $order = $this->action->execute($this->cart->refresh());
        $orderItem = $order->items->first();

        $taxLines = OrderTaxLine::query()
            ->where('taxable_type', $orderItem->getMorphClass())
            ->where('taxable_id', $orderItem->id)
            ->get();

        expect($order->tax_amount)->toBe(1800)
            ->and($orderItem->tax_amount)->toBe(1800)
            ->and($taxLines->sum('amount'))->toBe(1800)
            ->and($order->price_amount)->toBe(10800);
    });

    it('does not freeze stale tax lines onto an item that became fully discounted after a recalculation', function (): void {
        $country = Country::query()->where('cca2', 'US')->first()
            ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

        $taxZone = TaxZone::factory()->create([
            'country_id' => $country->id,
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'tax_zone_id' => $taxZone->id,
            'rate' => 20.00,
            'is_default' => true,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
            'country_id' => $country->id,
        ]);

        $this->cartManager->calculate($this->cart->refresh());

        $discount = Discount::factory()->create([
            'code' => 'FREE100',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 100,
            'apply_to' => DiscountApplyTo::Products,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonth(),
        ]);
        $discount->items()->create([
            'discountable_id' => $this->product->id,
            'discountable_type' => $this->product->getMorphClass(),
            'condition' => DiscountCondition::ApplyTo,
        ]);
        $this->cartManager->applyCoupon($this->cart, 'FREE100');

        $order = $this->action->execute($this->cart->refresh());
        $orderItem = $order->items->first();

        $taxLines = OrderTaxLine::query()
            ->where('taxable_type', $orderItem->getMorphClass())
            ->where('taxable_id', $orderItem->id)
            ->get();

        expect($orderItem->tax_amount)->toBe(0)
            ->and($taxLines)->toHaveCount(0)
            ->and($order->tax_amount)->toBe(0);
    });

    it('attributes the tax lines of each cart line to its own order item when rates differ', function (): void {
        $country = Country::query()->where('cca2', 'US')->first()
            ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

        $taxZone = TaxZone::factory()->create([
            'country_id' => $country->id,
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'tax_zone_id' => $taxZone->id,
            'rate' => 20.00,
            'is_default' => true,
        ]);

        $virtualRate = TaxRate::factory()->create([
            'tax_zone_id' => $taxZone->id,
            'rate' => 5.00,
            'is_default' => false,
        ]);
        $virtualRate->rules()->create([
            'reference_type' => 'product_type',
            'reference_id' => 'virtual',
        ]);

        $virtualProduct = Product::factory()->virtual()->create();
        $virtualProduct->prices()->create([
            'amount' => 1000,
            'currency_id' => $this->currency->id,
        ]);
        $virtualProduct->load('prices');
        $virtualProduct->mutateStock($this->inventory->id, 100);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->add($this->cart, $virtualProduct);
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
            'country_id' => $country->id,
        ]);

        $order = $this->action->execute($this->cart->refresh());

        $standardItem = $order->items->firstWhere('product_id', $this->product->id);
        $virtualItem = $order->items->firstWhere('product_id', $virtualProduct->id);

        $standardLines = OrderTaxLine::query()
            ->where('taxable_type', $standardItem->getMorphClass())
            ->where('taxable_id', $standardItem->id)
            ->get();
        $virtualLines = OrderTaxLine::query()
            ->where('taxable_type', $virtualItem->getMorphClass())
            ->where('taxable_id', $virtualItem->id)
            ->get();

        expect((float) $standardLines->sole()->rate)->toBe(20.0)
            ->and($standardItem->tax_amount)->toBe(5)
            ->and((float) $virtualLines->sole()->rate)->toBe(5.0)
            ->and($virtualItem->tax_amount)->toBe(50)
            ->and($order->tax_amount)->toBe(55);
    });

    it('keeps the tax invariant when one line is fully discounted and another is fully taxed', function (): void {
        $country = Country::query()->where('cca2', 'US')->first()
            ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

        $taxZone = TaxZone::factory()->create([
            'country_id' => $country->id,
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'tax_zone_id' => $taxZone->id,
            'rate' => 20.00,
            'is_default' => true,
        ]);

        $freeProduct = Product::factory()->standard()->create();
        $freeProduct->prices()->create([
            'amount' => 500,
            'currency_id' => $this->currency->id,
        ]);
        $freeProduct->load('prices');
        $freeProduct->mutateStock($this->inventory->id, 100);

        $discount = Discount::factory()->create([
            'code' => 'FREEITEM',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 100,
            'apply_to' => DiscountApplyTo::Products,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonth(),
        ]);
        $discount->items()->create([
            'discountable_id' => $freeProduct->id,
            'discountable_type' => $freeProduct->getMorphClass(),
            'condition' => DiscountCondition::ApplyTo,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->add($this->cart, $freeProduct);
        $this->cartManager->applyCoupon($this->cart, 'FREEITEM');
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
            'country_id' => $country->id,
        ]);

        $order = $this->action->execute($this->cart->refresh());

        $freeItem = $order->items->firstWhere('product_id', $freeProduct->id);
        $allTaxLines = OrderTaxLine::query()
            ->whereIn('taxable_id', $order->items->pluck('id'))
            ->get();

        expect($freeItem->tax_amount)->toBe(0)
            ->and(OrderTaxLine::query()->where('taxable_id', $freeItem->id)->count())->toBe(0)
            ->and($order->tax_amount)->toBe($allTaxLines->sum('amount'));
    });

    it('freezes the tax lines without stacking tax on the total for a tax-inclusive zone', function (): void {
        $country = Country::query()->where('cca2', 'FR')->first()
            ?? Country::factory()->create(['cca2' => 'FR', 'name' => 'France']);

        $taxZone = TaxZone::factory()->create([
            'country_id' => $country->id,
            'is_tax_inclusive' => true,
        ]);

        TaxRate::factory()->create([
            'tax_zone_id' => $taxZone->id,
            'rate' => 20.00,
            'is_default' => true,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->addAddress($this->cart, AddressType::Shipping, [
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'address_1' => '1 Rue de Paris',
            'city' => 'Paris',
            'postal_code' => '75001',
            'country_id' => $country->id,
        ]);

        $order = $this->action->execute($this->cart->refresh());
        $orderItem = $order->items->first();

        $taxLines = OrderTaxLine::query()
            ->where('taxable_type', $orderItem->getMorphClass())
            ->where('taxable_id', $orderItem->id)
            ->get();

        expect($order->price_amount)->toBe(25)
            ->and($order->tax_amount)->toBe($taxLines->sum('amount'))
            ->and($orderItem->tax_amount)->toBe($taxLines->sum('amount'))
            ->and((float) $taxLines->sole()->rate)->toBe(20.0);
    });

    it('passes a price drop since add-to-cart on to the customer', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $this->product->prices()->first()->update(['amount' => 20]);
        $this->product->load('prices');

        $order = $this->action->execute($this->cart->refresh());

        expect($order->items->first()->unit_price_amount)->toBe(20)
            ->and($order->price_amount)->toBe(40);
    });

    it('refuses to place the order when a line price increased since add-to-cart', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $this->product->prices()->first()->update(['amount' => 30]);
        $this->product->load('prices');

        expect(fn () => $this->action->execute($this->cart->refresh()))
            ->toThrow(PriceChangedException::class);

        expect(Order::query()->count())->toBe(0)
            ->and($this->cart->refresh()->isCompleted())->toBeFalse();
    });

    it('rolls back the whole order when the after-create hook fails', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        expect(fn () => $this->action->execute(
            $this->cart,
            afterCreate: function (): void {
                throw new RuntimeException('payment journal failed');
            },
        ))->toThrow(RuntimeException::class);

        expect(Order::query()->count())->toBe(0)
            ->and($this->cart->refresh()->isCompleted())->toBeFalse()
            ->and($this->product->refresh()->getStock())->toBe(100);
    });

    it('marks the cart as completed after order creation', function (): void {
        $this->cartManager->add($this->cart, $this->product);

        $this->action->execute($this->cart);

        expect($this->cart->refresh()->isCompleted())->toBeTrue();
    });

    it('dispatches `CartCompleted` event', function (): void {
        Event::fake([CartCompleted::class]);

        $this->cartManager->add($this->cart, $this->product);

        $this->action->execute($this->cart);

        Event::assertDispatched(CartCompleted::class, fn (CartCompleted $event): bool => $event->cart->id === $this->cart->id);
    });

    it('increments discount `total_use` when coupon is applied', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'SAVE10',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => 100,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'SAVE10');

        $this->action->execute($this->cart->refresh());

        expect($discount->refresh()->total_use)->toBe(1);
    });

    it('creates the order without a discount when the applied coupon is exhausted', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'LIMITED',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 5,
            'usage_limit' => 5,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'LIMITED');

        $order = $this->action->execute($this->cart->refresh());

        expect($order->discount_id)->toBeNull()
            ->and($order->discount_code)->toBeNull()
            ->and($discount->refresh()->total_use)->toBe(5)
            ->and($this->cart->refresh()->isCompleted())->toBeTrue();
    });

    it('does not reserve a usage slot when the coupon does not reduce the cart', function (): void {
        $other = Product::factory()->standard()->create();

        $discount = Discount::factory()->create([
            'code' => 'PRODUCTS_ONLY',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => 100,
            'apply_to' => DiscountApplyTo::Products,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);
        $discount->items()->create([
            'discountable_id' => $other->id,
            'discountable_type' => $other->getMorphClass(),
            'condition' => DiscountCondition::ApplyTo,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'PRODUCTS_ONLY');

        $order = $this->action->execute($this->cart->refresh());

        expect($order->discount_id)->toBeNull()
            ->and($discount->refresh()->total_use)->toBe(0);
    });

    it('snapshots the discount fields onto the order', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'SNAP10',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => null,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'SNAP10');

        $order = $this->action->execute($this->cart->refresh());

        expect($order->discount_id)->toBe($discount->id)
            ->and($order->discount_code)->toBe('SNAP10')
            ->and($order->discount_type)->toBe(DiscountType::Percentage->value)
            ->and($order->discount_value_at_apply)->toBe(10)
            ->and($order->discount_currency_code)->toBe('USD');
    });

    it('does not let a customer redeem a one-use-per-customer discount twice', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'ONCE',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => null,
            'usage_limit_per_user' => true,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'ONCE');

        $this->action->execute($this->cart->refresh());

        $secondCart = Cart::factory()->create([
            'currency_code' => 'USD',
            'customer_id' => $this->user->id,
        ]);
        $this->cartManager->add($secondCart, $this->product);
        $this->cartManager->applyCoupon($secondCart, 'ONCE');

        $secondOrder = $this->action->execute($secondCart->refresh());

        expect($secondOrder->discount_id)->toBeNull()
            ->and($discount->refresh()->total_use)->toBe(1)
            ->and(Order::query()->where('discount_id', $discount->id)->count())->toBe(1);
    });

    it('draws down the parent campaign budget when a campaign-backed coupon is redeemed', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 1_000)->create(['currency_code' => 'USD']);

        Discount::factory()->create([
            'code' => 'CAMP10',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => null,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'campaign_id' => $campaign->id,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'CAMP10');

        $order = $this->action->execute($this->cart->refresh());

        $campaign->refresh();

        expect($campaign->used_count)->toBe(1)
            ->and($campaign->spent_amount)->toBeGreaterThan(0)
            ->and(CampaignBudgetMovement::query()->where('order_id', $order->id)->count())->toBe(1);
    });

    it('snapshots every applied promotion onto the order and mirrors the largest on discount_*', function (): void {
        Discount::factory()->create([
            'code' => 'TEN', 'is_active' => true, 'type' => DiscountType::Percentage, 'value' => 10,
            'total_use' => 0, 'apply_to' => DiscountApplyTo::Order, 'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None, 'combinable' => true, 'priority' => 20,
        ]);
        $five = Discount::factory()->create([
            'code' => 'FIVE', 'is_active' => true, 'type' => DiscountType::Percentage, 'value' => 5,
            'total_use' => 0, 'apply_to' => DiscountApplyTo::Order, 'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None, 'combinable' => true, 'priority' => 10,
        ]);

        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cartManager->applyCoupon($this->cart, 'TEN');
        $this->cartManager->applyCoupon($this->cart, 'FIVE');

        $order = $this->action->execute($this->cart->refresh());

        expect($order->promotions)->toHaveCount(2)
            ->and($order->discount_code)->toBe('TEN')
            ->and($order->promotions->firstWhere('code', 'FIVE')->amount)->toBeGreaterThan(0)
            ->and($five->refresh()->total_use)->toBe(1);
    });

    it('reserves the parent campaign budget once for two promotions of the same campaign', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 1_000_000)->create(['currency_code' => 'USD']);

        foreach (['CA' => 20, 'CB' => 10] as $code => $priority) {
            Discount::factory()->create([
                'code' => $code, 'is_active' => true, 'type' => DiscountType::Percentage, 'value' => 5,
                'total_use' => 0, 'apply_to' => DiscountApplyTo::Order, 'eligibility' => DiscountEligibility::Everyone,
                'min_required' => DiscountRequirement::None, 'combinable' => true, 'priority' => $priority,
                'campaign_id' => $campaign->id,
            ]);
        }

        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cartManager->applyCoupon($this->cart, 'CA');
        $this->cartManager->applyCoupon($this->cart, 'CB');

        $order = $this->action->execute($this->cart->refresh());

        // One movement for the campaign, summing both promotions' amounts.
        expect(CampaignBudgetMovement::query()->where('order_id', $order->id)->count())->toBe(1)
            ->and($campaign->refresh()->used_count)->toBe(1)
            ->and($campaign->spent_amount)->toBe((int) $order->promotions->sum('amount'));
    });

    it('does not snapshot or burn the usage of a suppressed promotion', function (): void {
        Discount::factory()->create([
            'code' => 'EXCL30', 'is_active' => true, 'type' => DiscountType::Percentage, 'value' => 30,
            'total_use' => 0, 'usage_limit' => 100, 'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone, 'min_required' => DiscountRequirement::None,
            'combinable' => false, 'priority' => 30,
        ]);
        $suppressed = Discount::factory()->create([
            'code' => 'SUPP10', 'is_active' => true, 'type' => DiscountType::Percentage, 'value' => 10,
            'total_use' => 0, 'usage_limit' => 100, 'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone, 'min_required' => DiscountRequirement::None,
            'combinable' => true, 'priority' => 10,
        ]);

        $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cartManager->applyCoupon($this->cart, 'EXCL30');
        $this->cartManager->applyCoupon($this->cart, 'SUPP10');

        $order = $this->action->execute($this->cart->refresh());

        expect($order->promotions)->toHaveCount(1)
            ->and($order->promotions->first()->code)->toBe('EXCL30')
            ->and($suppressed->refresh()->total_use)->toBe(0);
    });

    it('completes checkout without the discount when the campaign budget is already exhausted', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 1)
            ->create(['currency_code' => 'USD', 'spent_amount' => 1]);

        $discount = Discount::factory()->create([
            'code' => 'CAMPFULL',
            'is_active' => true,
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
            'usage_limit' => null,
            'apply_to' => DiscountApplyTo::Order,
            'eligibility' => DiscountEligibility::Everyone,
            'min_required' => DiscountRequirement::None,
            'campaign_id' => $campaign->id,
        ]);

        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->applyCoupon($this->cart, 'CAMPFULL');

        // The validator strips the exhausted-campaign discount at calculation time,
        // so checkout succeeds at full price instead of throwing mid-transaction.
        $order = $this->action->execute($this->cart->refresh());

        expect($order->discount_id)->toBeNull()
            ->and($discount->refresh()->total_use)->toBe(0)
            ->and($campaign->refresh()->spent_amount)->toBe(1)
            ->and(CampaignBudgetMovement::query()->count())->toBe(0);
    });

    it('throws `CartCompletedException` for already completed cart', function (): void {
        $this->cart->update(['completed_at' => now()]);

        $this->action->execute($this->cart->refresh());
    })->throws(CartCompletedException::class);
})->group('cart', 'cart-order');

it('copies the checkout selections from the cart onto the order', function (): void {
    $method = PaymentMethod::factory()->create();
    $carrier = Carrier::factory()->create(['slug' => 'main-carrier', 'is_enabled' => true]);
    $option = CarrierOption::factory()->create(['carrier_id' => $carrier->id, 'price' => 950]);

    $this->cartManager->add($this->cart, $this->product);
    $this->cart->update([
        'payment_method_id' => $method->id,
        'shipping_option_id' => "main-carrier:{$option->public_id}",
        'shipping_amount' => 950,
    ]);

    $order = $this->action->execute($this->cart->refresh());

    expect($order->payment_method_id)->toBe($method->id)
        ->and($order->shipping_amount)->toBe(950)
        ->and($order->shipping_option_id)->toBe($option->id)
        ->and($order->price_amount)->toBe(25 + 950);
});

it('leaves `shipping_option_id` empty for live-carrier rates and still freezes the amount', function (): void {
    $this->cartManager->add($this->cart, $this->product);
    $this->cart->update([
        'shipping_option_id' => 'ups:express-03',
        'shipping_amount' => 1295,
    ]);

    $order = $this->action->execute($this->cart->refresh());

    expect($order->shipping_option_id)->toBeNull()
        ->and($order->shipping_amount)->toBe(1295)
        ->and($order->price_amount)->toBe(25 + 1295);
});
