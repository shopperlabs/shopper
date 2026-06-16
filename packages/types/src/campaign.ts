import type { Entity, Metadata } from './common'
import type { Discount } from './discount'

export enum CampaignBudgetType {
  NONE = 'none',
  SPEND = 'spend',
  COUNT = 'count',
  SPEND_AND_COUNT = 'spend_and_count',
}

export enum CampaignStatus {
  DRAFT = 'draft',
  SCHEDULED = 'scheduled',
  ACTIVE = 'active',
  DISABLED = 'disabled',
  EXPIRED = 'expired',
  BUDGET_EXHAUSTED = 'budget_exhausted',
}

/**
 * Campaign model.
 */
export interface Campaign extends Entity {
  /** The name of the campaign. */
  name: string
  /** The ISO currency code the budget is denominated in. */
  currency_code: string
  /** Whether the campaign is active. */
  is_active: boolean
  /** The budget cap type enforced by the campaign. */
  budget_type: CampaignBudgetType
  /** The spend cap in cents, or null when no spend cap. */
  budget_amount: number | null
  /** The usage count cap, or null when no count cap. */
  budget_count: number | null
  /** The amount spent against the budget, in cents. */
  spent_amount: number
  /** The number of times the campaign budget has been used. */
  used_count: number
  /** The computed status of the campaign. */
  status: CampaignStatus
  /** The start date of the campaign. */
  starts_at: string
  /** The end date of the campaign, or null when open-ended. */
  ends_at: string | null
  /** The metadata of the campaign. */
  metadata: Metadata
  /** The discounts attached to the campaign. */
  discounts?: Discount[]
}
