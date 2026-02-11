import type { Entity } from './common'
import type { Product } from './product'

/**
 * ProductTag model.
 */
export interface ProductTag extends Entity {
  /** The name of the tag. */
  name: string
  /** The slug of the tag. */
  slug: string | null
  /** The products associated with this tag. */
  products?: Product[]
}
