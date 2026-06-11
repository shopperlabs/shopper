# Shopper API

The headless Store API for [Shopper](https://shopper.cloud). It exposes the catalog, geo data,
customer authentication and account endpoints as a [JSON:API](https://jsonapi.org) under the
`/store` prefix, ready to be consumed by any storefront or by the official
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

## License

Shopper API is open-sourced software licensed under the [MIT license](https://github.com/shopperlabs/shopper/blob/2.x/LICENSE.md).
