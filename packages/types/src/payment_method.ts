import type { Entity } from './common'
import type { Zone } from './zone'

/**
 * PaymentMethod model.
 */
export interface PaymentMethod extends Entity {
  /** The title of the payment method. */
  title: string
  /** The slug of the payment method. */
  slug: string
  /** The logo path of the payment method. */
  logo: string | null
  /** The computed logo URL. */
  logo_url: string | null
  /** The link URL of the payment method. */
  link_url: string | null
  /** The description of the payment method. */
  description: string | null
  /** The instructions for the payment method. */
  instructions: string | null
  /** Whether the payment method is enabled. */
  is_enabled: boolean
  /** The zones this payment method belongs to. */
  zones?: Zone[]
}
