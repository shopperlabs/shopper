# @shopperlabs/shopper-sdk

Official JavaScript SDK for the [Shopper](https://shopper.cloud) headless commerce API.

The SDK talks to the Shopper Store API (JSON:API) and returns the plain, nested
shapes declared by [`@shopperlabs/shopper-types`](https://www.npmjs.com/package/@shopperlabs/shopper-types).
You work with `product.variants` as an array of full variants — the SDK handles
the JSON:API normalization (relationships + `included`) for you.

## Installation

```bash
npm install @shopperlabs/shopper-sdk
```

## Usage

```ts
import Shopper from '@shopperlabs/shopper-sdk'

const sdk = new Shopper({ baseUrl: 'https://my-store.com' })

// List products, filtered and paginated
const { data, meta } = await sdk.store.product.list({
  filter: { name: 'sony' },
  sort: ['-published_at'],
  page: { size: 12 },
})

// Retrieve a product with its relationships, fully nested
const product = await sdk.store.product.retrieve('sony-wh-1000xm5', {
  include: ['brand', 'variants', 'options', 'categories'],
})

product.variants?.forEach((variant) => console.log(variant.name, variant.prices))
```

## Custom & addon endpoints

Use `store.fetch()` for any endpoint not yet wrapped, including addon routes
registered through `shopper/http`. The JSON:API payload is flattened the same way:

```ts
const { data } = await sdk.store.fetch('/store/best-sellers', { query: { limit: 10 } })
```

## Errors

Non-2xx responses throw a `ShopperApiError` carrying the HTTP status and the
JSON:API error objects:

```ts
import Shopper, { ShopperApiError } from '@shopperlabs/shopper-sdk'

const sdk = new Shopper({ baseUrl: 'https://my-store.com' })

try {
  await sdk.store.product.retrieve('missing')
} catch (error) {
  if (error instanceof ShopperApiError) {
    console.error(error.status, error.errors)
  }
}
```
