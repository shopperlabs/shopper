<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Contracts\ProductPriceIndex;
use Shopper\Core\Contracts\QuantityRuleResolver;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Pricing\PricingContext;
use Shopper\Core\Pricing\QuantityRule;
use Tests\Cart\Stubs\TieredPriceResolver;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currencyId = Currency::query()->where('code', 'USD')->value('id');
    $this->resolver = new TieredPriceResolver;
    $this->app->instance(PriceResolver::class, $this->resolver);

    $this->priced = function (string $name, int $amount, ProductType $type = ProductType::Standard): Product {
        $product = Product::factory()->publish()->create(['name' => $name, 'type' => $type]);

        Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $this->currencyId,
            'amount' => $amount,
        ]);

        return $product;
    };

    $this->withVariants = function (string $name, int $count): Product {
        $product = Product::factory()->publish()->create(['name' => $name, 'type' => ProductType::Variant]);

        foreach (range(1, $count) as $index) {
            $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

            Price::factory()->create([
                'priceable_id' => $variant->id,
                'priceable_type' => $variant->getMorphClass(),
                'currency_id' => $this->currencyId,
                'amount' => 1000 + $index,
            ]);
        }

        return $product;
    };
});

describe('calculated_price', function (): void {
    it('exposes the price resolved for an anonymous shopper on a simple product', function (): void {
        $product = ($this->priced)('Mug', 1000);

        $this->getJson('/store/products/'.$product->slug)
            ->assertOk()
            ->assertJsonPath('data.attributes.calculated_price', [
                'amount' => 1000,
                'compare_amount' => 1500,
                'original_amount' => 1000,
                'currency_code' => 'USD',
                'meta' => null,
            ])
            ->assertJsonPath('data.attributes.quantity_rule', null);
    });

    it('resolves the price for the authenticated customer, zone and channel', function (): void {
        $product = ($this->priced)('Mug', 1000);
        $channel = Channel::factory()->create(['slug' => 'wholesale', 'is_enabled' => true, 'is_default' => false]);
        $product->channels()->attach($channel->id);
        $customer = User::factory()->create();
        Sanctum::actingAs($customer, ['store']);

        $response = $this->getJson('/store/products/'.$product->slug, ['X-Shopper-Channel' => 'wholesale'])
            ->assertOk()
            ->assertJsonPath('data.attributes.calculated_price.amount', 900);

        expect($this->resolver->lastContext)
            ->customerId->toBe($customer->id)
            ->channelId->toBe($channel->id)
            ->and($response->headers->get('Vary'))->toContain('Authorization')
            ->and($response->headers->get('Cache-Control'))->toContain('private');
    });

    it('serializes only the public part of the resolved meta', function (): void {
        $this->resolver->meta = ['public' => ['price_list' => ['id' => '01J', 'type' => 'sale']], 'rule' => 'secret'];
        $product = ($this->priced)('Mug', 1000);

        $this->getJson('/store/products/'.$product->slug)
            ->assertOk()
            ->assertJsonPath('data.attributes.calculated_price.meta', ['price_list' => ['id' => '01J', 'type' => 'sale']]);
    });

    it('exposes the quantity rule resolved for the shopper', function (): void {
        $this->app->instance(QuantityRuleResolver::class, new class implements QuantityRuleResolver
        {
            public function resolve(Priceable&Model $purchasable, PricingContext $context): QuantityRule
            {
                return new QuantityRule(minimum: 6, increment: 6);
            }
        });
        $product = ($this->priced)('Mug', 1000);

        $this->getJson('/store/products/'.$product->slug)
            ->assertOk()
            ->assertJsonPath('data.attributes.quantity_rule', ['minimum' => 6, 'maximum' => null, 'increment' => 6]);
    });

    it('resolves the included variants of a variant product in one preload', function (): void {
        $product = ($this->withVariants)('Tee', 20);

        $response = $this->getJson('/store/products/'.$product->slug.'?include=variants')
            ->assertOk()
            ->assertJsonMissingPath('data.attributes.calculated_price');

        $variants = collect($response->json('included'))->where('type', 'variants');

        expect($variants)->toHaveCount(20)
            ->and($variants->pluck('attributes.calculated_price.amount')->unique()->all())->toBe([1000])
            ->and($this->resolver->preloads)->toBe([20]);
    });

    it('keys a listing carrying calculated prices on the authorization header', function (): void {
        ($this->priced)('Mug', 1000);

        $response = $this->getJson('/store/products')->assertOk();

        expect($response->headers->get('Vary'))->toContain('Authorization')
            ->and($response->headers->get('Cache-Control'))->toContain('private');
    });

    it('keys a listing of variant products on the authorization header', function (): void {
        ($this->withVariants)('Tee', 2);

        $response = $this->getJson('/store/products?sort=price')->assertOk();

        expect($response->headers->get('Vary'))->toContain('Authorization');
    });

    it('preloads a listing page once', function (): void {
        foreach (range(1, 5) as $index) {
            ($this->priced)("Mug {$index}", 1000);
        }

        $this->getJson('/store/products')->assertOk()->assertJsonPath('data.0.attributes.calculated_price.amount', 1000);

        expect($this->resolver->preloads)->toBe([5]);
    });

    it('is absent where the purchasable is only included, such as a cart line', function (): void {
        $product = ($this->priced)('Mug', 1000);
        $cart = Cart::factory()->create(['currency_code' => 'USD']);
        $cart->lines()->create([
            'purchasable_type' => $product->getMorphClass(),
            'purchasable_id' => $product->id,
            'quantity' => 1,
            'unit_price_amount' => 1000,
        ]);

        $response = $this->getJson("/store/carts/{$cart->public_id}?include=lines.purchasable")->assertOk();

        $purchasable = collect($response->json('included'))->firstWhere('type', 'products');

        expect($purchasable['attributes'])->not->toHaveKey('calculated_price')
            ->and($this->resolver->calls)->toBe(0)
            ->and($response->headers->get('Vary') ?? '')->not->toContain('Authorization');
    });
});

describe(ProductPriceIndex::class, function (): void {
    beforeEach(function (): void {
        $this->cheap = ($this->priced)('Listed high', 5000);
        ($this->priced)('Listed low', 1000);

        $this->app->instance(ProductPriceIndex::class, new readonly class($this->cheap->id) implements ProductPriceIndex
        {
            public function __construct(private int $cheapId) {}

            public function minPriceExpression(Builder $query, int $currencyId, PricingContext $context): string
            {
                return "CASE WHEN {$query->getModel()->getTable()}.id = {$this->cheapId} THEN 10 ELSE 9000 END";
            }

            public function ranges(Collection $products, int $currencyId, PricingContext $context): Collection
            {
                return $products->mapWithKeys(fn (Model $product): array => [
                    $product->getKey() => (object) ['min_amount' => 10, 'max_amount' => 20],
                ]);
            }
        });
    });

    it('sorts, filters and ranges through the bound index', function (): void {
        $this->getJson('/store/products?sort=price')
            ->assertOk()
            ->assertJsonPath('data.0.attributes.name', 'Listed high')
            ->assertJsonPath('data.0.attributes.price_range', ['currency_code' => 'USD', 'min' => 10, 'max' => 20]);

        $this->getJson('/store/products?filter[price_max]=100&include=price_range')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.price_range.min', 10);
    });

    it('sorts a product only the bound index prices', function (): void {
        Product::factory()->publish()->create(['name' => 'Contract only', 'type' => ProductType::Standard]);

        expect(collect($this->getJson('/store/products?sort=price')->assertOk()->json('data'))->pluck('attributes.name'))
            ->toContain('Contract only');
    });

    it('walks cursor pagination on the bound expression', function (): void {
        $names = [];
        $url = '/store/products?sort=price&page[size]=1&page[cursor]=';

        do {
            $response = $this->getJson($url)->assertOk();
            $names = [...$names, ...collect($response->json('data'))->pluck('attributes.name')->all()];
            $url = $response->json('links.next');
        } while ($url !== null);

        expect($names)->toBe(['Listed high', 'Listed low']);
    });
});
