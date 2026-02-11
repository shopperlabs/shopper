import type { DateEntity, Entity, Metadata } from './common'
import type { Zone } from './zone'

export enum DiscountType {
  PERCENTAGE = 'percentage',
  FIXED_AMOUNT = 'fixed_amount',
}

export enum DiscountApplyTo {
  ORDER = 'order',
  SPECIFIC = 'specific',
}

export enum DiscountEligibility {
  EVERYONE = 'everyone',
  CUSTOMERS = 'specific_customers',
}

export enum DiscountCondition {
  APPLY_TO = 'apply_to',
  ELIGIBILITY = 'eligibility',
}

/**
 * Discount model.
 */
export interface Discount extends Entity {
  /** The discount code. */
  code: string
  /** The type of discount. */
  type: DiscountType
  /** The value of the discount. */
  value: number
  /** What the discount applies to. */
  apply_to: DiscountApplyTo
  /** The minimum required type. */
  min_required: string
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
  /** The zone ID this discount belongs to. */
  zone_id: number | null
  /** The metadata of the discount. */
  metadata: Metadata
  /** The start date of the discount. */
  start_at: DateEntity
  /** The end date of the discount. */
  end_at: DateEntity | null
  /** The zone of the discount. */
  zone?: Zone
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
