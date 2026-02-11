import type { Entity, Metadata, SEOFields } from './common'
import type { Media } from './media'
import type { Product } from './product'

/**
 * Category model.
 */
export interface Category extends Entity, SEOFields {
  /** The name of the category. */
  name: string
  /** The slug of the category. */
  slug: string
  /** The description of the category. */
  description: string | null
  /** Whether the category is enabled. */
  is_enabled: boolean
  /** The position of the category. */
  position: number
  /** The id of the parent category. */
  parent_id: number | null
  /** The metadata of the category. */
  metadata: Metadata
  /** The image of the category. */
  image?: Media | null
  /** The parent category. */
  parent?: Category
  /** The children categories. */
  children?: Category[]
  /** The products of the category. */
  products?: Product[]
  /** The slug path of the category. */
  slug_path?: string
}
