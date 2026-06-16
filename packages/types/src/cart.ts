import type { Channel } from './channel'
import type { Entity, Metadata } from './common'
import type { Country } from './country'
import type { Customer } from './customer'
import type { Discount } from './discount'
import type { PaymentMethod } from './payment_method'
import type { Product } from './product'
import type { ProductVariant } from './product_variant'
import type { TaxRate } from './tax'
import type { Zone } from './zone'

export enum CartAddressType {
  BILLING = 'billing',
  SHIPPING = 'shipping',
}

/**
 * Cart model.
 */
/**
 * A promotion applied to a cart (store API).
 */
export interface AppliedCartPromotion {
  /** The promotion code applied, or null for an automatic promotion. */
  code: string | null
  /** Whether the promotion was applied via a code or triggered automatically. */
  source: 'code' | 'automatic'
  /** The discount amount this promotion contributes, in cents. */
  amount: number
  /** `applied` when it reduces the cart, `suppressed` when stacked out by another promotion. */
  status: 'applied' | 'suppressed'
}

export interface Cart extends Entity {
  /** The currency code for the cart. */
  currency_code: string
  /** The contact email frozen on the order at completion (store API). */
  email?: string | null
  /** The promotions applied to the cart (store API). */
  promotions?: AppliedCartPromotion[]
  /** The date the cart was completed (converted to order). */
  completed_at: string | null
  /** The metadata of the cart. */
  metadata?: Metadata
  /** The number of lines in the cart (store API). */
  lines_count?: number
  /** The sum of the line quantities (store API). */
  lines_quantity?: number
  /** The sum of the line subtotals in cents, computed by the cart pipelines (store API). */
  subtotal?: number
  /** The discount total in cents, computed by the cart pipelines (store API). */
  discount_total?: number
  /** The tax total in cents, computed by the cart pipelines (store API). */
  tax_total?: number
  /** The frozen price of the selected shipping option in cents (store API). */
  shipping_total?: number
  /** The cart total in cents, computed by the cart pipelines (store API). */
  total?: number
  /** Whether taxes are already included in the prices (store API). */
  tax_inclusive?: boolean
  /** The composite id `{carrier_code}:{service_code}` of the selected shipping option. */
  shipping_option_id?: string | null
  /** The customer ID. */
  customer_id: number | null
  /** The channel ID. */
  channel_id: number | null
  /** The zone ID. */
  zone_id: number | null
  /** The cart lines. */
  lines?: CartLine[]
  /** The cart addresses. */
  addresses?: CartAddress[]
  /** The customer. */
  customer?: Customer | null
  /** The channel. */
  channel?: Channel | null
  /** The zone. */
  zone?: Zone | null
  /** The selected payment method, when expanded through `include=payment_method`. */
  payment_method?: PaymentMethod | null
}

/**
 * CartLine model. The store API serializes a line with its computed amounts
 * and exposes the purchasable as an expandable relationship
 * (`include=lines.purchasable`), typed by `purchasable_type`.
 */
export interface CartLine extends Entity {
  /** The cart ID. */
  cart_id?: number
  /** Whether the line holds a product or one of its variants. */
  purchasable_type?: 'product' | 'variant' | string
  /** The morph ID of the purchasable entity. */
  purchasable_id?: number
  /** The quantity of the cart line. */
  quantity: number
  /** The unit price amount (in cents). */
  unit_price_amount: number
  /** The line subtotal in cents (unit price times quantity, store API). */
  subtotal?: number
  /** The discount amount applied to this line in cents (store API). */
  discount_total?: number
  /** The tax amount applied to this line in cents (store API). */
  tax_total?: number
  /** The metadata of the cart line. */
  metadata: Metadata
  /** The cart this line belongs to. */
  cart?: Cart
  /** The purchasable entity, when expanded through `include=lines.purchasable`. */
  purchasable?: Product | ProductVariant | null
  /** The price adjustments applied to this line. */
  adjustments?: CartLineAdjustment[]
  /** The tax lines applied to this line. */
  tax_lines?: CartLineTaxLine[]
}

/**
 * CartAddress model.
 */
export interface CartAddress extends Entity {
  /** The cart ID. */
  cart_id: number
  /** The address type (billing or shipping). */
  type: CartAddressType
  /** The country ID. */
  country_id?: number | null
  /** The ISO 3166-1 alpha-2 country code (store API). */
  country_code?: string | null
  /** The first name. */
  first_name: string | null
  /** The last name. */
  last_name: string
  /** The company name. */
  company: string | null
  /** The primary street address. */
  address_1: string
  /** The secondary street address. */
  address_2: string | null
  /** The city. */
  city: string
  /** The state/province. */
  state: string | null
  /** The postal code. */
  postal_code: string
  /** The phone number. */
  phone: string | null
  /** The computed full name. */
  full_name: string
  /** The cart this address belongs to. */
  cart?: Cart
  /** The country. */
  country?: Country | null
}

/**
 * CartLineAdjustment model — price adjustments (discounts) applied to a cart line.
 * The store API only carries the amount and the code on each line.
 */
export interface CartLineAdjustment {
  /** The internal id of the entity (admin). */
  id?: string | number
  /** The cart line ID. */
  cart_line_id?: number
  /** The adjustment amount (in cents). */
  amount: number
  /** The coupon/discount code. */
  code: string | null
  /** The discount ID (if applied from a discount). */
  discount_id?: number | null
  /** The cart line this adjustment belongs to. */
  cart_line?: CartLine
  /** The discount this adjustment comes from. */
  discount?: Discount | null
}

/**
 * CartLineTaxLine model — tax applied to a cart line.
 * The store API only carries the code, name, rate and amount on each line.
 */
export interface CartLineTaxLine {
  /** The internal id of the entity (admin). */
  id?: string | number
  /** The cart line ID. */
  cart_line_id?: number
  /** The tax code (e.g., "VAT", "GST"). */
  code: string
  /** The tax name (e.g., "TVA 20%"). */
  name: string
  /** The tax rate percentage (e.g., 20.0 for 20%). */
  rate: number
  /** The calculated tax amount (in cents). */
  amount: number
  /** The source tax rate ID. */
  tax_rate_id?: number | null
  /** The cart line this tax line belongs to. */
  cart_line?: CartLine
  /** The source tax rate. */
  tax_rate?: TaxRate | null
}
