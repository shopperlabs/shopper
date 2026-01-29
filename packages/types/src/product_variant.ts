import type { AttributeValue } from './attribute'
import type { Entity, Metadata, Price, ShippingFields } from './common'
import type { Media } from './media'
import type { Product } from './product'

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
  /** The stock quantity of the variant. */
  stock: number
  /** The metadata of the product variant. */
  metadata: Metadata
  /** The product this variant belongs to. */
  product?: Product
  /** The attribute values of this variant. */
  values?: AttributeValue[]
  /** The images of the product variant. */
  images?: Media[] | null
  /** The prices of the product variant. */
  prices?: Price[]
}
