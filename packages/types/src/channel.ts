import type { Entity, Metadata } from './common'

/**
 * Channel model.
 */
export interface Channel extends Entity {
  /** The name of the channel. */
  name: string
  /** The slug of the channel. */
  slug: string | null
  /** The description of the channel. */
  description: string | null
  /** The timezone of the channel. */
  timezone: string | null
  /** The URL of the channel. */
  url: string | null
  /** Whether the channel is the default. */
  is_default: boolean
  /** Whether the channel is enabled. */
  is_enabled: boolean
  /** The metadata of the channel. */
  metadata: Metadata
}
