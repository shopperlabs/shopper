import type { DateEntity, Entity, Metadata, SEOFields } from './common'
import type { Media } from './media'
import type { Zone } from './zone'

export enum CollectionType {
  MANUAL = 'manual',
  AUTO = 'auto',
}

export enum CollectionCondition {
  ALL = 'all',
  ANY = 'any',
}

export enum CollectionRuleType {
  PRODUCT_TITLE = 'product_title',
  PRODUCT_PRICE = 'product_price',
  COMPARE_AT_PRICE = 'compare_at_price',
  INVENTORY_STOCK = 'inventory_stock',
  PRODUCT_CATEGORY = 'product_category',
  PRODUCT_BRAND = 'product_brand',
}

export enum CollectionOperator {
  EQUALS = 'equals',
  NOT_EQUALS = 'not_equals',
  LESS_THAN = 'less_than',
  GREATER_THAN = 'greater_than',
  STARTS_WITH = 'starts_with',
  ENDS_WITH = 'ends_with',
  CONTAINS = 'contains',
  NOT_CONTAINS = 'not_contains',
}

/**
 * Collection model.
 */
export interface Collection extends Entity, SEOFields {
  /** The name of the collection. */
  name: string
  /** The slug of the collection. */
  slug: string
  /** The description of the collection. */
  description?: string | null
  /** The type of the collection. */
  type: CollectionType
  /** The match conditions for automatic collections. */
  match_conditions: CollectionCondition | null
  /** The sort order for products. */
  sort: string | null
  /** The published date of the collection. */
  published_at: DateEntity | null
  /** The metadata of the collection. */
  metadata: Metadata
  /** The image of the collection. */
  image?: Media | null
  /** The rules for automatic collections. */
  rules?: CollectionRule[]
  /** The zones this collection belongs to. */
  zones?: Zone[]
}

/**
 * CollectionRule model.
 */
export interface CollectionRule extends Entity {
  /** The rule type. */
  rule: CollectionRuleType
  /** The operator for the rule. */
  operator: CollectionOperator
  /** The value for the rule. */
  value: string
  /** The collection ID this rule belongs to. */
  collection_id: number
  /** The collection this rule belongs to. */
  collection?: Collection
}
