import type { Entity, Metadata, Price, ShippingFields } from './common'
import type { Media } from './media'
import type { Product } from './product'

/**
 * A variant's option value, denormalized for the storefront (e.g. Color = Black).
 * Used to match a selected option set to a variant and render swatches.
 */
export interface VariantOptionValue {
  /** The display value (e.g. "Black"). */
  value: string
  /** The key identifier / swatch value (e.g. "black" or a hex color). */
  key: string
  /** The option name this value belongs to (e.g. "Color"). */
  attribute: string
  /** The option slug. */
  attribute_slug: string
}

/**
 * ProductVariant model.
 */
export interface ProductVariant extends Entity, ShippingFields {
  /** The name of the product variant. */
  name: string
  /** The Stock Keeping Unit (SKU) code of the product variant. */
  sku?: string | null
  /** The barcode of the product variant. */
  barcode: string | null
  /** The EAN code of the product variant. */
  ean: string | null
  /** The UPC code of the product variant. */
  upc: string | null
  /** The position of the product variant. */
  position: number
  /** Whether backorders are allowed. */
  allow_backorder: boolean
  /** The product ID this variant belongs to. */
  product_id: number
  /** The stock quantity of the variant (exposed once batched stock loading lands). */
  stock?: number
  /** The metadata of the product variant. */
  metadata: Metadata
  /** The product this variant belongs to. */
  product?: Product
  /** The option values this variant is built from (Color = Black, Storage = 256, ...). */
  values?: VariantOptionValue[]
  /** The images of the product variant. */
  images?: Media[] | null
  /** The thumbnail of the product variant. */
  thumbnail?: Media | null
  /** The prices of the product variant. */
  prices?: Price[]
}
