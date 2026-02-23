import type { Entity, Metadata } from './common'
import type { Media } from './media'
import type { Zone } from './zone'

/**
 * PaymentMethod model.
 */
export interface PaymentMethod extends Entity {
  /** The title of the payment method. */
  title: string
  /** The slug of the payment method. */
  slug: string
  /** The link URL of the payment method. */
  link_url: string | null
  /** The description of the payment method. */
  description: string | null
  /** The instructions for the payment method. */
  instructions: string | null
  /** Whether the payment method is enabled. */
  is_enabled: boolean
  /** The payment driver identifier. */
  driver: string | null
  /** The metadata of the payment method. */
  metadata: Metadata
  /** The logo of the payment method. */
  logo?: Media | null
  /** The zones this payment method belongs to. */
  zones?: Zone[]
}
