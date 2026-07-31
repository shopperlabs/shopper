import type { Entity } from './common'

/**
 * A product review as the store API exposes it: approved reviews only, the
 * author reduced to a public-safe display identity.
 */
export interface Review extends Entity {
  /** The title of the review. */
  title: string | null
  /** The content of the review. */
  content: string | null
  /** The rating (1-5). */
  rating: number
  /** Whether the reviewer recommends the product. */
  is_recommended: boolean
  /** The reviewer's public identity, null when the account no longer exists. */
  author: ReviewAuthor | null
}

/**
 * The reviewer as shown next to a review. The avatar is only present when
 * one was uploaded; build the fallback (initials, generated image) from
 * `name` on the storefront.
 */
export interface ReviewAuthor {
  /** Display name (first name and last initial). */
  name: string | null
  /** URL of the uploaded avatar, null when none was uploaded. */
  avatar: string | null
}

/**
 * Review aggregates returned in the `meta` of a product's reviews listing,
 * computed server-side over the same approved predicate as the rows.
 */
export interface ReviewAggregates {
  /** Total number of approved reviews. */
  reviews_count: number
  /** Average rating rounded to one decimal, null without reviews. */
  average_rating: number | null
  /** Number of approved reviews per rating value (1-5). */
  rating_distribution: Record<number, number>
}
