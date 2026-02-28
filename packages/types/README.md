<p align="center">
  <img src="https://github.com/shopperlabs/art/blob/main/logomark.svg" alt="Shopper Logo" height="150" />
</p>

# Shopper Types definitions

TypeScript types derived from the OpenAPI Spec (OAS) to be used in API clients for Shopper.

## Install

```bash
npm install @shopper/types
# or
yarn add @shopper/types
```

## Usage

```typescript
import type { Product, Order, Customer } from '@shopper/types'

const product: Product = {
  id: 1,
  name: 'My Product',
  slug: 'my-product',
  // ...
}
```

## Available Types

### Models
- `Product`
- `ProductVariant`
- `Category`
- `Brand`
- `Collection`
- `Order`
- `OrderItem`
- `Customer`
- `Address`
- `Inventory`
- `Discount`
- `Review`
- `Channel`
- `Currency`
- `PaymentMethod`
- `Attribute`
- `AttributeValue`
- `Media`

### Enums
- `ProductType`
- `CollectionType`
- `CollectionCondition`
- `AddressType`
- `GenderType`
- `Weight`
- `Length`
- `Volume`

### Common Interfaces
- `Entity`
- `DateEntity`
- `Price`
- `ShippingFields`
- `SEOFields`
