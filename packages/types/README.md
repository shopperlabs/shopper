<p align="center">
  <img src="https://github.com/shopperlabs/art/blob/main/logomark.svg" alt="Shopper Logo" height="150" />
</p>

# Shopper Types definitions

TypeScript types for the resources of the Shopper API, used by [`@shopperlabs/shopper-sdk`](https://www.npmjs.com/package/@shopperlabs/shopper-sdk) and by any client of the API.

## Install

```bash
npm install @shopperlabs/shopper-types
# or
yarn add @shopperlabs/shopper-types
```

## Usage

```typescript
import type { Product, Order, Customer } from '@shopperlabs/shopper-types'

const product: Product = {
  id: 1,
  name: 'My Product',
  slug: 'my-product',
  // ...
}
```

## Available Types

Every resource of the Shopper API has its interface, exported from the package root along with its enums and the shared shapes (`Entity`, `Price`, `SEOFields`, `ShippingFields`).
