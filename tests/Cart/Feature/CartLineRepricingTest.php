<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Shopper\Cart\Actions\CreateOrderFromCartAction;
use Shopper\Cart\CartManager;
use Shopper\Cart\CartSessionManager;
use Shopper\Cart\Events\CartLinesRepriced;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\CartLineLockedException;
use Shopper\Cart\Exceptions\MissingPriceException;
use Shopper\Cart\Exceptions\PriceChangedException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Models\Zone;
use Tests\Cart\Stubs\TieredPriceResolver;
use Tests\Core\Stubs\User;

uses(Tests\Cart\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->user = User::factory()->create();
    $this->inventory = Inventory::factory()->create();

    $this->resolver = new TieredPriceResolver;
    $this->app->instance(PriceResolver::class, $this->resolver);
    $this->cartManager = resolve(CartManager::class);

    $this->product = Product::factory()->standard()->create();
    $this->product->prices()->create(['amount' => 1000, 'currency_id' => $this->currency->id]);
    $this->product->mutateStock($this->inventory->id, 100);

    $this->cart = Cart::factory()->create(['currency_code' => 'USD']);
});

describe('CartManager line repricing', function (): void {
    it('reprices a line when its quantity crosses a tier', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 9);

        $line = $this->cartManager->update($this->cart, $line->id, ['quantity' => 10]);

        expect($line->unit_price_amount)->toBe(800)
            ->and($line->pricing)->toBe(['tier' => 10]);
    });

    it('does not reprice a line when only its metadata changes', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);
        $this->resolver->base = 2000;

        $line = $this->cartManager->update($this->cart, $line->id, ['metadata' => ['gift' => true]]);

        expect($line->unit_price_amount)->toBe(1000);
    });

    it('reprices a line when adding the same purchasable crosses a tier', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 5);

        $line = $this->cartManager->add($this->cart, $this->product, quantity: 5);

        expect($line->quantity)->toBe(10)
            ->and($line->unit_price_amount)->toBe(800);
    });

    it('keeps the frozen price when no price resolves anymore', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 9);
        $this->resolver->available = false;

        $line = $this->cartManager->update($this->cart, $line->id, ['quantity' => 10]);

        expect($line->unit_price_amount)->toBe(1000);
    });

    it('rejects a second line for the same purchasable at the database level', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);

        DB::table($line->getTable())->insert([
            'cart_id' => $this->cart->id,
            'purchasable_type' => $line->purchasable_type,
            'purchasable_id' => $line->purchasable_id,
            'quantity' => 1,
            'unit_price_amount' => 1000,
        ]);
    })->throws(UniqueConstraintViolationException::class);

    it('retries an add that lost a race on the unique line index', function (): void {
        $raced = false;

        CartLine::creating(function () use (&$raced): void {
            if (! $raced) {
                $raced = true;

                throw new UniqueConstraintViolationException('testing', 'insert', [], new PDOException);
            }
        });

        $line = $this->cartManager->add($this->cart, $this->product, quantity: 6);

        expect($raced)->toBeTrue()
            ->and($this->cart->lines()->count())->toBe(1)
            ->and($line->quantity)->toBe(6);
    });
});

describe('Duplicate cart lines migration', function (): void {
    it('folds existing duplicate lines before adding the unique index', function (): void {
        $migration = include __DIR__.'/../../../packages/cart/database/migrations/2026_09_26_000002_add_purchasable_unique_index_to_cart_lines_table.php';
        $migration->down();

        $table = shopper_table('cart_lines');
        $row = [
            'cart_id' => $this->cart->id,
            'purchasable_type' => $this->product->getMorphClass(),
            'purchasable_id' => $this->product->id,
            'unit_price_amount' => 1000,
        ];
        $keptId = DB::table($table)->insertGetId([...$row, 'quantity' => 2]);
        DB::table($table)->insert([...$row, 'quantity' => 3]);

        $migration->up();

        expect(DB::table($table)->where('cart_id', $this->cart->id)->pluck('quantity', 'id')->all())->toBe([$keptId => 5]);
    });
});

describe('CartManager::reprice', function (): void {
    it('reprices every line in the cart context and reports the changes', function (): void {
        Event::fake([CartLinesRepriced::class]);

        $line = $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->cart->update(['customer_id' => $this->user->id]);

        $changes = $this->cartManager->reprice($this->cart);

        expect($changes)->toHaveCount(1)
            ->and($changes->first())->toMatchArray(['from' => 1000, 'to' => 900])
            ->and($line->refresh()->unit_price_amount)->toBe(900);

        Event::assertDispatched(CartLinesRepriced::class, fn (CartLinesRepriced $event): bool => $event->changes->count() === 1);
    });

    it('raises a line price when the new context resolves higher', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);
        $this->resolver->base = 1500;

        $changes = $this->cartManager->reprice($this->cart);

        expect($changes->first())->toMatchArray(['from' => 1000, 'to' => 1500])
            ->and($line->refresh()->unit_price_amount)->toBe(1500);
    });

    it('does not dispatch an event when nothing changed', function (): void {
        Event::fake([CartLinesRepriced::class]);

        $this->cartManager->add($this->cart, $this->product);

        expect($this->cartManager->reprice($this->cart))->toBeEmpty();

        Event::assertNotDispatched(CartLinesRepriced::class);
    });

    it('resets the payment session when a reprice changes an amount', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->setPaymentSession($this->cart, ['reference' => 'pi_1', 'amount' => 1000]);
        $this->resolver->base = 1200;

        $this->cartManager->reprice($this->cart);

        expect($this->cart->refresh()->payment_session)->toBeNull();
    });

    it('keeps the payment session when no amount changed', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->setPaymentSession($this->cart, ['reference' => 'pi_1', 'amount' => 1000]);
        $this->resolver->meta = ['price_list' => ['id' => 'pl_2']];

        $this->cartManager->reprice($this->cart);

        expect($this->cart->refresh()->payment_session)->toEqual(['reference' => 'pi_1', 'amount' => 1000]);
    });

    it('does not rewrite a line whose stored pricing only differs in key order', function (): void {
        $this->resolver->meta = ['tier' => 1, 'price_list' => ['name' => 'B2B', 'id' => 'pl_1'], 'source' => 'price_list'];
        $line = $this->cartManager->add($this->cart, $this->product);
        CartLine::query()->whereKey($line->id)->toBase()->update(['pricing' => json_encode(['price_list' => ['id' => 'pl_1', 'name' => 'B2B'], 'tier' => 1, 'source' => 'price_list'])]);
        $updatedAt = $line->refresh()->updated_at;
        $this->travel(1)->minute();

        $this->cartManager->reprice($this->cart);

        expect($line->refresh()->updated_at)->toEqual($updatedAt);
    });

    it('rewrites a line whose stored pricing lists the same values in another order', function (): void {
        $this->resolver->meta = ['applied' => ['b', 'a']];
        $line = $this->cartManager->add($this->cart, $this->product);
        CartLine::query()->whereKey($line->id)->toBase()->update(['pricing' => json_encode(['applied' => ['a', 'b']])]);

        $this->cartManager->reprice($this->cart);

        expect($line->refresh()->pricing)->toBe(['applied' => ['b', 'a']]);
    });

    it('reprices a cart in a number of queries that does not grow with its lines', function (): void {
        $queriesFor = function (int $lines): int {
            $cart = Cart::factory()->create(['currency_code' => 'USD']);

            foreach (range(1, $lines) as $index) {
                $product = Product::factory()->standard()->create();
                $product->prices()->create(['amount' => 1000, 'currency_id' => $this->currency->id]);
                $product->mutateStock($this->inventory->id, 10);
                $this->cartManager->add($cart, $product);
            }

            DB::flushQueryLog();
            DB::enableQueryLog();
            $this->cartManager->reprice($cart->refresh());
            $count = count(DB::getQueryLog());
            DB::disableQueryLog();

            return $count;
        };

        $small = $queriesFor(3);
        $large = $queriesFor(30);

        expect($large)->toBe($small)
            ->and(array_slice($this->resolver->preloads, -1))->toBe([30]);
    });

    it('preloads the lines of the cart when checkout revalidates their prices', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->preloads = [];

        resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh());

        expect($this->resolver->preloads)->toBe([1]);
    });

    it('preloads the lines of the customer cart once when a guest cart merges into it', function (): void {
        $other = Product::factory()->standard()->create();
        $other->mutateStock($this->inventory->id, 10);
        $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $this->user->id]);
        $this->cartManager->add($this->cart, $other);
        $this->cartManager->add($customerCart, $this->product);
        $this->resolver->preloads = [];

        $this->cartManager->merge($this->cart, $customerCart);

        expect($this->resolver->preloads)->toBe([2]);
    });

    it('does not resolve an unrelated line when a line quantity changes', function (): void {
        $other = Product::factory()->standard()->create();
        $other->prices()->create(['amount' => 500, 'currency_id' => $this->currency->id]);
        $other->mutateStock($this->inventory->id, 10);
        $this->cartManager->add($this->cart, $other);
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 9);
        $this->resolver->calls = 0;

        $this->cartManager->update($this->cart, $line->id, ['quantity' => 10]);

        expect($this->resolver->calls)->toBe(1);
    });

    it('refuses to change the currency when a line has no price in it', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->available = false;

        $this->cartManager->changeCurrency($this->cart, 'EUR');
    })->throws(MissingPriceException::class);

    it('refuses a line update that a concurrent completion overtook', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 2);
        DB::table($this->cart->getTable())->where('id', $this->cart->id)->update(['completed_at' => now()]);

        $this->cartManager->update($this->cart, $line->id, ['quantity' => 5]);
    })->throws(CartCompletedException::class);

    it('refuses to reprice a completed cart', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->cart->update(['completed_at' => now()]);

        $this->cartManager->reprice($this->cart);
    })->throws(CartCompletedException::class);

    it('refuses to reprice a cart a concurrent merge deleted', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        DB::table($this->cart->getTable())->where('id', $this->cart->id)->delete();

        $this->cartManager->reprice($this->cart);
    })->throws(ModelNotFoundException::class);
});

describe('CartManager::changeContext', function (): void {
    it('reprices the cart on a change of channel alone', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->base = 1100;
        $channel = Channel::factory()->create();

        $this->cartManager->changeContext($this->cart, zoneId: null, channelId: $channel->id);

        expect($this->cart->refresh()->channel_id)->toBe($channel->id)
            ->and($this->cart->lines()->first()->unit_price_amount)->toBe(1100)
            ->and($this->resolver->lastContext->channelId)->toBe($channel->id);
    });

    it('stores the zone and channel, reprices and resets the shipping and payment choices', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->cart->update(['shipping_option_id' => 'carrier:standard', 'shipping_amount' => 500, 'payment_method_id' => PaymentMethod::factory()->create()->id]);
        $this->cartManager->setPaymentSession($this->cart, ['reference' => 'pi_1', 'amount' => 1500]);
        $this->resolver->base = 1100;

        $zone = Zone::factory()->create();

        $this->cartManager->changeContext($this->cart, zoneId: $zone->id, channelId: null);

        expect($this->cart->refresh())
            ->zone_id->toBe($zone->id)
            ->shipping_option_id->toBeNull()
            ->shipping_amount->toBeNull()
            ->payment_method_id->toBeNull()
            ->payment_session->toBeNull()
            ->and($this->cart->lines()->first()->unit_price_amount)->toBe(1100);
    });
});

describe('Manually priced lines', function (): void {
    it('locks a line to a server-set price', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);

        $line = $this->cartManager->setLinePrice($this->cart, $line->id, 450);
        $this->resolver->base = 2000;
        $this->cartManager->reprice($this->cart);

        expect($line->refresh())
            ->unit_price_amount->toBe(450)
            ->is_custom_price->toBeTrue();
    });

    it('refuses to change the currency of a cart with a manually priced line', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->setLinePrice($this->cart, $line->id, 450);

        $this->cartManager->changeCurrency($this->cart, 'EUR');
    })->throws(CartLineLockedException::class);

    it('accepts a manual price of zero', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);

        expect($this->cartManager->setLinePrice($this->cart, $line->id, 0))
            ->unit_price_amount->toBe(0)
            ->is_custom_price->toBeTrue();
    });

    it('refuses a negative manual price', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);

        $this->cartManager->setLinePrice($this->cart, $line->id, -1);
    })->throws(InvalidArgumentException::class);

    it('refuses to change the quantity of a manually priced line', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 10);
        $this->cartManager->setLinePrice($this->cart, $line->id, 450);

        $this->cartManager->update($this->cart, $line->id, ['quantity' => 1000]);
    })->throws(CartLineLockedException::class);

    it('refuses to add more of a manually priced purchasable', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 10);
        $this->cartManager->setLinePrice($this->cart, $line->id, 450);

        $this->cartManager->add($this->cart, $this->product, quantity: 990);
    })->throws(CartLineLockedException::class);

    it('refuses to merge a guest line into a manually priced line', function (): void {
        $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $this->user->id]);
        $locked = $this->cartManager->add($customerCart, $this->product, quantity: 10);
        $this->cartManager->setLinePrice($customerCart, $locked->id, 450);
        $this->cartManager->add($this->cart, $this->product, quantity: 5);

        $this->cartManager->merge($this->cart, $customerCart);
    })->throws(CartLineLockedException::class);

    it('refuses to merge a manually priced guest line into an existing customer line', function (): void {
        $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $this->user->id]);
        $this->cartManager->add($customerCart, $this->product, quantity: 5);
        $guest = $this->cartManager->add($this->cart, $this->product, quantity: 10);
        $this->cartManager->setLinePrice($this->cart, $guest->id, 450);

        $this->cartManager->merge($this->cart, $customerCart);
    })->throws(CartLineLockedException::class);

    it('refuses to move a manually priced guest line into a cart in another currency', function (): void {
        $customerCart = Cart::factory()->create(['currency_code' => 'EUR', 'customer_id' => $this->user->id]);
        $guest = $this->cartManager->add($this->cart, $this->product, quantity: 10);
        $this->cartManager->setLinePrice($this->cart, $guest->id, 450);

        $this->cartManager->merge($this->cart, $customerCart);
    })->throws(CartLineLockedException::class);

    it('keeps the manual price when releasing a line that no longer resolves a price', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->setLinePrice($this->cart, $line->id, 0);
        $this->resolver->available = false;

        try {
            $this->cartManager->clearLinePrice($this->cart, $line->id);

            $this->fail('A line without a resolvable price was released.');
        } catch (MissingPriceException) {
            expect($line->refresh())
                ->unit_price_amount->toBe(0)
                ->is_custom_price->toBeTrue();
        }
    });

    it('releases a manual price back to the resolved price', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 10);
        $this->cartManager->setLinePrice($this->cart, $line->id, 450);

        $line = $this->cartManager->clearLinePrice($this->cart, $line->id);

        expect($line)
            ->unit_price_amount->toBe(800)
            ->is_custom_price->toBeFalse();
    });

    it('refuses to set a manual price on a completed cart', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);
        $this->cart->update(['completed_at' => now()]);

        $this->cartManager->setLinePrice($this->cart, $line->id, 450);
    })->throws(CartCompletedException::class);

    it('does not let mass assignment lock a line', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);

        $line->update(['is_custom_price' => true, 'pricing' => ['forged' => true]]);

        expect($line->refresh())
            ->is_custom_price->toBeFalse()
            ->pricing->toBe(['tier' => 1]);
    });
});

describe('Customer context changes', function (): void {
    it('reprices a guest line merged into an existing customer line', function (): void {
        $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $this->user->id]);
        $this->cartManager->add($customerCart, $this->product, quantity: 4);
        $this->cartManager->add($this->cart, $this->product, quantity: 6);

        $merged = $this->cartManager->merge($this->cart, $customerCart);

        expect($merged->lines()->first())
            ->quantity->toBe(10)
            ->unit_price_amount->toBe(700);
    });

    it('reprices a guest line moved into the customer cart at the same currency', function (): void {
        $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $this->user->id]);
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $merged = $this->cartManager->merge($this->cart, $customerCart);

        expect($merged->lines()->first()->unit_price_amount)->toBe(900);
    });

    it('claims the guest cart as it is when a manually priced line blocks the merge', function (): void {
        $customerCart = Cart::factory()->create(['currency_code' => 'USD', 'customer_id' => $this->user->id]);
        $locked = $this->cartManager->add($customerCart, $this->product, quantity: 10);
        $this->cartManager->setLinePrice($customerCart, $locked->id, 450);

        $session = resolve(CartSessionManager::class);
        $session->use($this->cart);
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $session->associate($this->user);

        expect($this->cart->refresh()->customer_id)->toBe($this->user->id)
            ->and($this->cart->lines()->first()->unit_price_amount)->toBe(900)
            ->and($locked->refresh()->quantity)->toBe(10);
    });

    it('rolls a merge back when a moved line has no price in the customer currency', function (): void {
        $customerCart = Cart::factory()->create(['currency_code' => 'EUR', 'customer_id' => $this->user->id]);
        $line = $this->cartManager->add($this->cart, $this->product, quantity: 2);
        $this->resolver->available = false;

        try {
            $this->cartManager->merge($this->cart, $customerCart);

            $this->fail('A line without a price in the customer currency was moved.');
        } catch (MissingPriceException) {
            expect($line->refresh()->cart_id)->toBe($this->cart->id)
                ->and(Cart::query()->whereKey($this->cart->id)->exists())->toBeTrue();
        }
    });

    it('reports only the price changes of lines that stay in their currency when a guest cart merges', function (): void {
        Event::fake([CartLinesRepriced::class]);
        $other = Product::factory()->standard()->create();
        $other->mutateStock($this->inventory->id, 10);
        $customerCart = Cart::factory()->create(['currency_code' => 'EUR', 'customer_id' => $this->user->id]);
        $kept = $this->cartManager->add($customerCart, $other);
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->base = 1500;

        $this->cartManager->merge($this->cart, $customerCart);

        Event::assertDispatched(CartLinesRepriced::class, fn (CartLinesRepriced $event): bool => $event->changes->pluck('line.id')->all() === [$kept->id]);
    });

    it('reprices the session cart when a customer is associated', function (): void {
        $session = resolve(CartSessionManager::class);
        $session->use($this->cart);
        $this->cartManager->add($this->cart, $this->product, quantity: 2);

        $session->associate($this->user);

        expect($this->cart->lines()->first()->unit_price_amount)->toBe(900);
    });
});

describe('Tiers across the variants of a product', function (): void {
    beforeEach(function (): void {
        $this->resolver->tierOnProduct = true;

        [$this->small, $this->large] = collect(['S', 'L'])->map(function (): ProductVariant {
            $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
            $variant->prices()->create(['amount' => 1000, 'currency_id' => $this->currency->id]);
            $variant->mutateStock($this->inventory->id, 50);

            return $variant;
        })->all();
    });

    it('passes the product quantity summed across variant lines', function (): void {
        $this->cartManager->add($this->cart, $this->small, quantity: 6);
        $large = $this->cartManager->add($this->cart, $this->large, quantity: 4);

        expect($large->unit_price_amount)->toBe(800)
            ->and($this->cart->lines()->pluck('unit_price_amount')->all())->toBe([800, 800]);
    });

    it('stays off a product tier one unit short across variant lines', function (): void {
        $this->cartManager->add($this->cart, $this->small, quantity: 5);
        $this->cartManager->add($this->cart, $this->large, quantity: 4);

        expect($this->cart->lines()->pluck('unit_price_amount')->all())->toBe([1000, 1000]);
    });

    it('reprices sibling lines when a variant line quantity changes', function (): void {
        $small = $this->cartManager->add($this->cart, $this->small, quantity: 6);
        $large = $this->cartManager->add($this->cart, $this->large, quantity: 4);

        $this->cartManager->update($this->cart, $large->id, ['quantity' => 1]);

        expect($small->refresh()->unit_price_amount)->toBe(1000);
    });

    it('reprices sibling lines when a variant line is removed', function (): void {
        $small = $this->cartManager->add($this->cart, $this->small, quantity: 6);
        $large = $this->cartManager->add($this->cart, $this->large, quantity: 4);

        $this->cartManager->remove($this->cart, $large->id);

        expect($small->refresh()->unit_price_amount)->toBe(1000);
    });

    it('keeps an item-scoped tier per variant line', function (): void {
        $this->resolver->tierOnProduct = false;

        $this->cartManager->add($this->cart, $this->small, quantity: 6);
        $this->cartManager->add($this->cart, $this->large, quantity: 4);

        expect($this->cart->lines()->pluck('unit_price_amount')->all())->toBe([1000, 1000]);
    });

    it('revalidates a product-scoped tier at checkout without a price change', function (): void {
        $this->cartManager->add($this->cart, $this->small, quantity: 6);
        $this->cartManager->add($this->cart, $this->large, quantity: 4);

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh());

        expect($order->items->pluck('unit_price_amount')->all())->toBe([800, 800]);
    });
});

describe('Checkout', function (): void {
    it('honours a manually priced line at checkout', function (): void {
        $line = $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->setLinePrice($this->cart, $line->id, 450);

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh());

        expect($order->items->first()->unit_price_amount)->toBe(450);
    });

    it('applies a lower live price at checkout', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->base = 700;

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh());

        expect($order->items->first()->unit_price_amount)->toBe(700);
    });

    it('honours the current prices when the caller says they were paid', function (int $livePrice): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->base = $livePrice;

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh(), honoursPayment: fn (): bool => true);

        expect($order->items->first()->unit_price_amount)->toBe(1000);
    })->with([800, 1200]);

    it('refuses a higher live price the caller does not honour', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->base = 1200;

        resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh(), honoursPayment: fn (): bool => false);
    })->throws(PriceChangedException::class);

    it('refuses the checkout of a line no resolver prices anymore', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->available = false;

        resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh(), honoursPayment: fn (): bool => false);
    })->throws(MissingPriceException::class);

    it('honours a line no resolver prices anymore when the caller says it was paid', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->available = false;

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh(), honoursPayment: fn (): bool => true);

        expect($order->items->first()->unit_price_amount)->toBe(1000);
    });

    it('records the pricing that sets an unchanged price at checkout', function (): void {
        $this->cartManager->add($this->cart, $this->product);
        $this->resolver->meta = ['price_list' => ['id' => 'pl_2']];

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh(), honoursPayment: fn (): bool => true);

        expect($order->items->first())
            ->unit_price_amount->toBe(1000)
            ->pricing->toBe(['price_list' => ['id' => 'pl_2']]);
    });

    it('records the pricing of unchanged lines when the caller honours a moved one', function (): void {
        $other = Product::factory()->standard()->create();
        $other->prices()->create(['amount' => 1000, 'currency_id' => $this->currency->id]);
        $other->mutateStock($this->inventory->id, 100);
        $moved = $this->cartManager->add($this->cart, $this->product);
        $this->cartManager->add($this->cart, $other);
        $moved->forceFill(['unit_price_amount' => 900])->save();
        $this->resolver->meta = ['price_list' => ['id' => 'pl_2']];

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh(), honoursPayment: fn (): bool => true);

        expect($order->items->pluck('pricing', 'unit_price_amount')->all())->toBe([
            900 => ['tier' => 1],
            1000 => ['price_list' => ['id' => 'pl_2']],
        ]);
    });

    it('copies the line pricing onto the order item', function (): void {
        $this->cartManager->add($this->cart, $this->product, quantity: 10);

        $order = resolve(CreateOrderFromCartAction::class)->execute($this->cart->refresh());

        expect($order->items->first()->pricing)->toBe(['tier' => 10]);
    });
});
