import type { Campaign } from './campaign'
import type { Entity, Metadata } from './common'
import type { Zone } from './zone'

export enum DiscountType {
  PERCENTAGE = 'percentage',
  FIXED_AMOUNT = 'fixed_amount',
}

export enum DiscountApplyTo {
  ORDER = 'order',
  PRODUCTS = 'products',
}

export enum DiscountEligibility {
  EVERYONE = 'everyone',
  CUSTOMERS = 'customers',
}

export enum DiscountRequirement {
  NONE = 'none',
  PRICE = 'price',
  QUANTITY = 'quantity',
}

export enum DiscountCondition {
  APPLY_TO = 'apply_to',
  ELIGIBILITY = 'eligibility',
}

export enum PromotionSource {
  CODE = 'code',
  AUTOMATIC = 'automatic',
}

export enum ExclusivityClass {
  ORDER = 'order',
  PRODUCT = 'product',
  SHIPPING = 'shipping',
}

/**
 * Discount model.
 */
export interface Discount extends Entity {
  /** The discount code, or null for an automatic promotion. */
  code: string | null
  /** Whether the discount is applied via a code or triggered automatically. */
  trigger: PromotionSource
  /** The type of discount. */
  type: DiscountType
  /** The value of the discount. */
  value: number
  /** What the discount applies to. */
  apply_to: DiscountApplyTo
  /** The minimum required type. */
  min_required: DiscountRequirement
  /** The minimum required value. */
  min_required_value: string | null
  /** Who is eligible for this discount. */
  eligibility: DiscountEligibility
  /** The usage limit for the discount. */
  usage_limit: number | null
  /** The total number of times the discount has been used. */
  total_use: number
  /** Whether the discount has a per-user usage limit. */
  usage_limit_per_user: boolean
  /** Whether the discount is active. */
  is_active: boolean
  /** The exclusivity class used when stacking promotions. */
  exclusivity_class: ExclusivityClass
  /** Whether the discount can be combined with other discounts. */
  combinable: boolean
  /** The resolution priority when stacking promotions. */
  priority: number
  /** The campaign ID this discount belongs to, or null when standalone. */
  campaign_id: number | null
  /** The zone ID this discount belongs to. */
  zone_id: number | null
  /** The metadata of the discount. */
  metadata: Metadata
  /** The start date of the discount. */
  start_at: string
  /** The end date of the discount. */
  end_at: string | null
  /** The zone of the discount. */
  zone?: Zone
  /** The campaign the discount belongs to. */
  campaign?: Campaign
  /** The discount items/details. */
  items?: DiscountDetail[]
}

/**
 * DiscountDetail model (discountables).
 */
export interface DiscountDetail extends Entity {
  /** The condition type for this detail. */
  condition: DiscountCondition
  /** The type of the discountable entity. */
  discountable_type: string
  /** The ID of the discountable entity. */
  discountable_id: number
  /** The discount ID this detail belongs to. */
  discount_id: number
  /** The total usage of this detail. */
  total_use: number
  /** The discount this detail belongs to. */
  discount?: Discount
}
