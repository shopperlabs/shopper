import type { Entity, Metadata, ResourceId, SEOFields } from './common'
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
  /** The public id of the parent category, null on a root category. */
  parent_id: ResourceId | null
  /** The metadata of the category. */
  metadata: Metadata
  /** The thumbnail of the category. */
  thumbnail?: Media | null
  /** The parent category. */
  parent?: Category
  /** The children categories. */
  children?: Category[]
  /** The enabled ancestors of the category, root first. */
  ancestors?: Category[]
  /** The products of the category. */
  products?: Product[]
  /** The number of ancestors above the category, zero on a root. */
  depth?: number | null
  /** The number of distinct public products in the category subtree. */
  products_count?: number
}

/**
 * A node of the public category tree, children nested recursively.
 */
export interface CategoryTreeNode {
  id: ResourceId
  name: string
  slug: string
  position: number
  children: CategoryTreeNode[]
}
