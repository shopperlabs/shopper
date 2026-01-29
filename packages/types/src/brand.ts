import type { Entity, Metadata, SEOFields } from './common'
import type { Media } from './media'

/**
 * Brand model.
 */
export interface Brand extends Entity, SEOFields {
  /** The name of the brand. */
  name: string
  /** The slug of the brand. */
  slug: string | null
  /** The website URL of the brand. */
  website: string | null
  /** The description of the brand. */
  description: string | null
  /** The position of the brand. */
  position: number
  /** Whether the brand is enabled. */
  is_enabled: boolean
  /** The metadata of the brand. */
  metadata: Metadata
  /** The image of the brand. */
  image?: Media | null
}
