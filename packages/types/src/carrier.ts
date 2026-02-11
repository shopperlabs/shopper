import type { Entity, Metadata } from './common'
import type { Media } from './media'
import type { Zone } from './zone'

/**
 * Carrier model.
 */
export interface Carrier extends Entity {
  /** The name of the carrier. */
  name: string
  /** The slug of the carrier. */
  slug: string | null
  /** The shipping driver identifier. */
  driver: string | null
  /** The description of the carrier. */
  description: string | null
  /** The link URL of the carrier. */
  link_url: string | null
  /** The shipping amount (in cents). */
  shipping_amount: number | null
  /** Whether the carrier is enabled. */
  is_enabled: boolean
  /** The metadata of the carrier. */
  metadata: Metadata
  /** The logo of the carrier. */
  logo?: Media | null
  /** The options of this carrier. */
  options?: CarrierOption[]
  /** The zones this carrier belongs to. */
  zones?: Zone[]
}

/**
 * CarrierOption model.
 */
export interface CarrierOption extends Entity {
  /** The name of the carrier option. */
  name: string
  /** The description of the carrier option. */
  description: string | null
  /** The price of the carrier option (in cents). */
  price: number
  /** Whether the carrier option is enabled. */
  is_enabled: boolean
  /** The zone ID this option belongs to. */
  zone_id: number
  /** The carrier ID this option belongs to. */
  carrier_id: number
  /** The metadata of the carrier option. */
  metadata: Metadata
  /** The zone of the carrier option. */
  zone?: Zone
  /** The carrier of the carrier option. */
  carrier?: Carrier
}
