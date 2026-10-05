<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Shopper\Api\Actions\TransferCartAction;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Http\Resources\JsonApiResource;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;

final class CartTransferController
{
    use RespondsWithCart;

    public function __construct(
        private readonly TransferCartAction $action,
    ) {}

    /**
     * Transfer a guest cart to the authenticated customer.
     *
     * Used when a guest signs in mid checkout: the cart they built is attached
     * to their account, and folded into the cart they already owned when one
     * exists. The response carries the resulting cart; persist its id, the
     * guest id is gone after a merge. Idempotent for a cart they already own,
     * refused for a cart that belongs to another customer.
     */
    public function __invoke(Request $request, string $cartId): JsonApiResource
    {
        $cart = $this->findCartOrFail($cartId);
        $customerId = $request->user()->getAuthIdentifier();

        $currencies = $cart->newQuery()
            ->whereKey($cart->getKey())
            ->orWhere(fn (Builder $query): Builder => $query->where('customer_id', $customerId)->whereNull('completed_at'))
            ->pluck('currency_code', $cart->getKeyName());

        $before = $cart->lines()->getRelated()->newQuery()
            ->whereIn('cart_id', $currencies->keys())
            ->get(['id', 'cart_id', 'unit_price_amount'])
            ->keyBy('id');

        $cart = $this->mutateCart(fn (): Cart => $this->action->execute(
            cart: $cart,
            customerId: $customerId,
        ))->refresh();

        $changes = $cart->lines
            ->map(fn (CartLine $line): array => [
                'line' => $line->public_id,
                'from' => $this->previousPrice($before->get($line->id), $currencies, $cart->currency_code),
                'to' => (int) $line->unit_price_amount,
            ])
            ->filter(fn (array $change): bool => $change['from'] !== null && $change['from'] !== $change['to'])
            ->values()
            ->all();

        return $this->cartResource($cart)->additional(['meta' => ['price_changes' => $changes]]);
    }

    /**
     * @param  Collection<int|string, string>  $currencies
     */
    private function previousPrice(?CartLine $line, Collection $currencies, string $currencyCode): ?int
    {
        if ($line === null || $currencies->get($line->cart_id) !== $currencyCode) {
            return null;
        }

        return (int) $line->unit_price_amount;
    }
}
