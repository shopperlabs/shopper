# Shopper API

The headless Store API for [Shopper](https://laravelshopper.me). It exposes the catalog, geo data,
cart, customer authentication and account endpoints as a [JSON:API](https://jsonapi.org) under
the `/store` prefix, ready to be consumed by any storefront or by the official
[`@shopperlabs/shopper-sdk`](https://www.npmjs.com/package/@shopperlabs/shopper-sdk) JavaScript client.

## Requirements

- PHP 8.3+
- Laravel 12.x or 13.x
- [`shopper/framework`](https://github.com/shopperlabs/shopper) installed and migrated

## Installation

```bash
composer require shopper/api
php artisan shopper:api:install
```

The service provider is auto-discovered and registers every `/store` route. No route file
needs to be published.

The install command takes care of everything the API needs after the package is required:

- publishes the [Laravel Sanctum](https://laravel.com/docs/sanctum) migrations
  (`personal_access_tokens` is required by the auth endpoints)
- publishes the Shopper configuration files
- adds the `HasApiTokens` trait to your configured user model, with your confirmation
- runs the database migrations

### Manual setup

Prefer doing it by hand? The auth endpoints issue stateless Sanctum tokens, so publish and
run the Sanctum migration once:

```bash
php artisan vendor:publish --tag=sanctum-migrations
php artisan migrate
```

Skipping this step makes every auth endpoint fail with a "relation personal_access_tokens
does not exist" error.

Then customers authenticate against the user model configured in `auth.providers.users.model`,
which needs the Sanctum trait:

```php
use Laravel\Sanctum\HasApiTokens;
use Shopper\Traits\InteractsWithShopper;

class User extends Authenticatable implements ShopperUser
{
    use HasApiTokens;
    use InteractsWithShopper;
}
```

`InteractsWithShopper` is already part of every Shopper installation. It gives the model the
customer columns, the avatar accessor, and the `public_id` ULID exposed as the JSON:API id.

## Endpoints

All routes live under the configurable `/store` prefix (`SHOPPER_API_PREFIX`), are throttled,
and answer in `application/vnd.api+json`.

| Area | Endpoints |
|---|---|
| Catalog | `GET /store/products[/{slug}]`, `categories`, `collections`, `brands`, `attributes` |
| Geo | `GET /store/countries[/{code}]`, `zones[/{code}]`, `currencies[/{code}]` |
| Cart | `POST /store/carts`, `GET /store/carts/{id}`, `POST /store/carts/{id}/lines`, `PATCH/DELETE /store/carts/{id}/lines/{lineId}` |
| Auth | `POST /store/auth/register`, `login`, `logout`, `forgot-password`, `reset-password` |
| Account | `GET/PATCH /store/customers/me`, `POST/DELETE /store/customers/me/avatar`, addresses CRUD, `GET /store/customers/me/orders` |

Lists support the JSON:API query family: `filter[...]`, `sort`, `include`,
`page[number]`/`page[size]` and sparse fieldsets via `fields[type]`. The allowed filters,
sorts and includes per resource are driven by the `shopper.api.resources` config and can be
extended in your application.

## Configuration

```bash
php artisan vendor:publish --tag=shopper-config
```

This publishes `config/shopper/api.php` (pagination, per-resource query allowlists) and
`config/shopper/http.php` (route prefix, rate limiters, zone resolution, extra middleware).

### Pricing context

Prices are scoped by zone. The storefront pins one by sending its code on every request:

```
X-Shopper-Zone: eu
```

The resolver is swappable through `shopper.http.zone.resolver` if you prefer resolving the
zone from a country, a geo-ip lookup, or any custom strategy.

## Cart

The cart works for guests and authenticated customers with the same endpoints. Create one,
persist its id on the client (cookie, localStorage), then drive it through the line endpoints:

```
POST   /store/carts                        → 201, the new cart
GET    /store/carts/{id}                   → 200, summary + totals
POST   /store/carts/{id}/lines             → 200, add a purchasable
PATCH  /store/carts/{id}/lines/{lineId}    → 200, change quantity / metadata
DELETE /store/carts/{id}/lines/{lineId}    → 200, remove the line
```

Every response is the full cart document, recalculated by the `shopper/cart` pipelines
(lines, coupon discounts, taxes), so the storefront never needs a follow-up call to refresh
totals. All amounts are integers in cents.

```json
{
  "data": {
    "type": "carts",
    "id": "01jxd9se2cd6kr925vqgve46rb",
    "attributes": {
      "currency_code": "USD",
      "coupon_code": "SAVE20",
      "lines_count": 1,
      "lines_quantity": 2,
      "subtotal": 5000,
      "discount_total": 1000,
      "tax_total": 400,
      "total": 4400,
      "tax_inclusive": false
    }
  }
}
```

### Includes

The cart document is partitioned: the attributes above are the cheap summary (enough for a
header badge), everything heavier is an opt-in [JSON:API include](https://jsonapi.org/format/#fetching-includes):

| Include | Expands |
|---|---|
| `lines` | The cart lines with their computed amounts (`subtotal`, `discount_total`, `tax_total`), applied `adjustments` and `tax_lines` |
| `lines.purchasable` | The full product or variant behind each line, typed by the line's `purchasable_type` |
| `addresses` | The shipping and billing addresses attached to the cart |

```
GET /store/carts/{id}?include=lines.purchasable,addresses
```

The `include` parameter works on the line endpoints too, so a `POST .../lines?include=lines`
answers with the updated lines in the same round trip.

### Adding to the cart

Lines target a purchasable through its public id and a type discriminator:

```json
{
  "purchasable_type": "variant",
  "purchasable_id": "01jxd9se2cd6kr925vqgve46rb",
  "quantity": 2,
  "metadata": { "engraving": "MC" }
}
```

Line operations are idempotent on the purchasable: adding the same product or variant again
increments the existing line instead of creating a duplicate. The API refuses anything a
storefront cannot sell, draft products, external products, products that sell through their
variants, or a purchasable without a price in the cart currency, with a `422` validation error.

### Ownership

A guest cart is a capability URL: whoever holds the ULID can read and mutate it. A cart
created with a Bearer token belongs to that customer, and any other caller gets a `404`
indistinguishable from a missing cart. Mutating a completed cart answers `409 Conflict`.

## Authentication flow

```
POST /store/auth/register   → 201, customer + Sanctum token in meta.token
POST /store/auth/login      → 200, customer + token in meta.token
POST /store/auth/logout     → 204, revokes the current token
```

Send the token as a Bearer header on every account endpoint:

```
Authorization: Bearer {token}
```

Tokens carry the `store` ability. The customer is always resolved from the token, so account
URLs never contain a customer id.

## JavaScript SDK

The [`@shopperlabs/shopper-sdk`](https://www.npmjs.com/package/@shopperlabs/shopper-sdk)
package wraps every endpoint, manages the token automatically and flattens the JSON:API
payloads into the types from `@shopperlabs/shopper-types`:

```ts
import Shopper from '@shopperlabs/shopper-sdk'

const sdk = new Shopper({ baseUrl: 'https://my-store.com' })

await sdk.auth.login({ email, password })
const me = await sdk.store.customer.me()
const { data } = await sdk.store.product.list({ include: ['variants', 'brand'] })
```

The cart module mirrors the cart endpoints and asks for the `lines` include by default, so
`cart.lines` is always populated. Pass your own `include` to slim it down or expand more:

```ts
let cart = await sdk.store.cart.create({ currency_code: 'USD' })

cart = await sdk.store.cart.createLineItem(cart.id, {
  purchasable_type: 'variant',
  purchasable_id: variant.public_id,
  quantity: 2,
})

cart = await sdk.store.cart.updateLineItem(cart.id, cart.lines[0].id, { quantity: 3 })
cart = await sdk.store.cart.deleteLineItem(cart.id, cart.lines[0].id)

// Header badge: skip the lines entirely
const summary = await sdk.store.cart.retrieve(cart.id, { include: [] })
console.log(summary.lines_quantity, summary.total)
```

## License

Shopper API is open-sourced software licensed under the [MIT license](https://github.com/shopperlabs/shopper/blob/3.x/LICENSE.md).
