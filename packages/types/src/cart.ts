import type { Channel } from './channel'
import type { DateEntity, Entity, Metadata } from './common'
import type { Country } from './country'
import type { Customer } from './customer'
import type { Discount } from './discount'
import type { TaxRate } from './tax'
import type { Zone } from './zone'

export enum CartAddressType {
  BILLING = 'billing',
  SHIPPING = 'shipping',
}

/**
 * Cart model.
 */
export interface Cart extends Entity {
  /** The currency code for the cart. */
  currency_code: string
  /** The coupon code applied to the cart. */
  coupon_code: string | null
  /** The date the cart was completed (converted to order). */
  completed_at: DateEntity | null
  /** The metadata of the cart. */
  metadata: Metadata
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
}

/**
 * CartLine model.
 */
export interface CartLine extends Entity {
  /** The cart ID. */
  cart_id: number
  /** The morph type of the purchasable entity. */
  purchasable_type: string
  /** The morph ID of the purchasable entity. */
  purchasable_id: number
  /** The quantity of the cart line. */
  quantity: number
  /** The unit price amount (in cents). */
  unit_price_amount: number
  /** The metadata of the cart line. */
  metadata: Metadata
  /** The cart this line belongs to. */
  cart?: Cart
  /** The purchasable entity (product or variant). */
  purchasable?: Record<string, unknown>
  /** The price adjustments applied to this line. */
  adjustments?: CartLineAdjustment[]
  /** The tax lines applied to this line. */
  taxLines?: CartLineTaxLine[]
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
  country_id: number | null
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
 */
export interface CartLineAdjustment extends Entity {
  /** The cart line ID. */
  cart_line_id: number
  /** The adjustment amount (in cents). */
  amount: number
  /** The coupon/discount code. */
  code: string | null
  /** The discount ID (if applied from a discount). */
  discount_id: number | null
  /** The cart line this adjustment belongs to. */
  cartLine?: CartLine
  /** The discount this adjustment comes from. */
  discount?: Discount | null
}

/**
 * CartLineTaxLine model — tax applied to a cart line.
 */
export interface CartLineTaxLine extends Entity {
  /** The cart line ID. */
  cart_line_id: number
  /** The tax code (e.g., "VAT", "GST"). */
  code: string
  /** The tax name (e.g., "TVA 20%"). */
  name: string
  /** The tax rate percentage (e.g., 20.0 for 20%). */
  rate: number
  /** The calculated tax amount (in cents). */
  amount: number
  /** The source tax rate ID. */
  tax_rate_id: number | null
  /** The cart line this tax line belongs to. */
  cartLine?: CartLine
  /** The source tax rate. */
  taxRate?: TaxRate | null
}
