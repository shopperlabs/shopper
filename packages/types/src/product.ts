import type { Attribute } from './attribute'
import type { Brand } from './brand'
import type { Category } from './category'
import type { Channel } from './channel'
import type { Collection } from './collection'
import type {
  DateEntity,
  Entity,
  Metadata,
  Price,
  SEOFields,
  ShippingFields,
} from './common'
import type { Media } from './media'
import type { ProductTag } from './product_tag'
import type { ProductVariant } from './product_variant'
import type { Review } from './review'
import type { Supplier } from './supplier'

export enum ProductType {
  EXTERNAL = 'external',
  VIRTUAL = 'virtual',
  STANDARD = 'standard',
  VARIANT = 'variant',
}

/**
 * Product model.
 */
export interface Product extends Entity, SEOFields, ShippingFields {
  /** The name of the product. */
  name: string
  /** The slug of the product. */
  slug: string
  /** The Stock Keeping Unit (SKU) code of the product. */
  sku?: string | null
  /** The barcode of the product. */
  barcode: string | null
  /** The summary of the product. */
  summary: string | null
  /** The description of the product. */
  description: string | null
  /** The security stock of the product. */
  security_stock: number | null
  /** Whether the product is featured. */
  featured: boolean
  /** Whether the product is visible. */
  is_visible: boolean
  /** Whether backorders are allowed. */
  allow_backorder: boolean
  /** The type of the product. */
  type: ProductType | null
  /** The published at date of the product. */
  published_at: DateEntity | null
  /** The external ID of the product. */
  external_id: string | null
  /** The stock quantity of the product. */
  stock: number
  /** The combined stock of all variants. */
  variants_stock?: number
  /** The supplier ID of the product. */
  supplier_id?: number | null
  /** The brand ID of the product. */
  brand_id: number | null
  /** The metadata of the product. */
  metadata: Metadata
  /** The supplier of the product. */
  supplier?: Supplier
  /** The brand of the product. */
  brand?: Brand
  /** The channels of the product. */
  channels?: Channel[]
  /** The categories of the product. */
  categories?: Category[]
  /** The options/attributes of the product. */
  options?: Attribute[]
  /** The collections of the product. */
  collections?: Collection[]
  /** The tags of the product. */
  tags?: ProductTag[]
  /** The variants of the product. */
  variants?: ProductVariant[]
  /** The related products. */
  relatedProducts?: Product[]
  /** The images of the product. */
  images?: Media[] | null
  /** The reviews of the product. */
  reviews?: Review[]
  /** The prices of the product. */
  prices?: Price[]
}
