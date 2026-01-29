import type { Entity } from './common'
import type { AvatarType } from './customer'

/**
 * Review model.
 */
export interface Review extends Entity {
  /** Whether the review is recommended. */
  is_recommended: boolean
  /** The rating (usually 1-5). */
  rating: number
  /** The title of the review. */
  title: string | null
  /** The content of the review. */
  content: string | null
  /** Whether the review is approved. */
  approved: boolean
  /** The ID of the reviewable entity. */
  reviewrateable_id: number
  /** The type of the reviewable entity. */
  reviewrateable_type: string
  /** The ID of the author. */
  author_id: number
  /** The type of the author. */
  author_type: string
  /** The author of the review. */
  author?: ReviewAuthor
}

/**
 * ReviewAuthor type for author information.
 */
export type ReviewAuthor = {
  last_name: string
  first_name: string
  avatar: AvatarType
}
