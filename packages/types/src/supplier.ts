import type { Entity, Metadata } from './common'

/**
 * Supplier model.
 */
export interface Supplier extends Entity {
  /** The name of the supplier. */
  name: string
  /** The slug of the supplier. */
  slug: string | null
  /** The email of the supplier. */
  email: string | null
  /** The phone number of the supplier. */
  phone: string | null
  /** The contact name at the supplier. */
  contact_name: string | null
  /** The website URL of the supplier. */
  website: string | null
  /** The description of the supplier. */
  description: string | null
  /** Additional notes about the supplier. */
  notes: string | null
  /** Whether the supplier is enabled. */
  is_enabled: boolean
  /** The metadata of the supplier. */
  metadata: Metadata
}
