<p align="center">
  <img src="https://github.com/shopperlabs/art/blob/main/logomark.svg" alt="Shopper Logo" height="150" />
</p>

# Shopper JavaScript SDK

<p>
  <a href="https://www.npmjs.com/package/@shopperlabs/shopper-sdk">
    <img src="https://img.shields.io/npm/v/@shopperlabs/shopper-sdk" alt="Latest Version" />
  </a>
  <a href="https://www.npmjs.com/package/@shopperlabs/shopper-sdk">
    <img src="https://img.shields.io/npm/dm/@shopperlabs/shopper-sdk" alt="Downloads" />
  </a>
  <a href="https://www.npmjs.com/package/@shopperlabs/shopper-sdk">
    <img src="https://img.shields.io/npm/l/@shopperlabs/shopper-sdk" alt="License" />
  </a>
  <a href="https://www.typescriptlang.org">
    <img src="https://img.shields.io/badge/types-included-blue" alt="Types Included" />
  </a>
</p>

Official JavaScript SDK for the [Shopper](https://laravelshopper.dev) headless commerce API.

The SDK talks to the Shopper API (JSON:API) and returns the plain, nested shapes declared by [`@shopperlabs/shopper-types`](https://www.npmjs.com/package/@shopperlabs/shopper-types).
You work with `product.variants` as an array of full variants the SDK handles the JSON:API normalization (relationships + `included`) for you. It runs in any
environment with a `fetch` implementation: browsers, Node.js 18+, React Native, edge runtimes.

## Installation

```bash
npm install @shopperlabs/shopper-sdk
```

## Quick start

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

## Documentation

The full SDK documentation — configuration, authentication, cart and checkout, custom endpoints, error handling lives at [docs.laravelshopper.dev](https://docs.laravelshopper.dev).

## License

Shopper SDK is open-sourced software licensed under the [MIT license](https://github.com/shopperlabs/shopper/blob/2.x/LICENSE.md).
