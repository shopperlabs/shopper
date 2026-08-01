import type { Entity, ResourceId } from './common'

export enum FieldType {
  TEXT = 'text',
  NUMBER = 'number',
  RICH_TEXT = 'richtext',
  SELECT = 'select',
  CHECKBOX = 'checkbox',
  COLOR_PICKER = 'colorpicker',
  DATE_PICKER = 'datepicker',
}

/**
 * Attribute model.
 */
export interface Attribute extends Entity {
  /** The name of the attribute. */
  name: string
  /** The slug of the attribute. */
  slug: string
  /** The type of the attribute field. */
  type?: FieldType
  /** The icon of the attribute. */
  icon?: string | null
  /** The description of the attribute. */
  description?: string | null
  /** Whether the attribute is enabled. */
  is_enabled?: boolean
  /** Whether the attribute is searchable. */
  is_searchable?: boolean
  /** Whether the attribute is filterable. */
  is_filterable?: boolean
  /** The computed formatted type. */
  type_formatted?: string
  /** The values of the attribute. */
  values?: AttributeValue[]
}

/**
 * AttributeValue model.
 */
export interface AttributeValue {
  /** The internal id (admin contexts only). */
  id?: ResourceId
  /** The display value. */
  value: string | number
  /** The key identifier (used as the facet filter value). */
  key: string
  /** The position/order. */
  position: number
  /**
   * Per-product swatch image URL for this value, shown instead of (or alongside)
   * the hex `key`. Present on a product's `options` include; null when the value
   * has no image for that product. The same value can carry a different image
   * per product.
   */
  swatch_url?: string | null
  /** The attribute ID this value belongs to (admin contexts only). */
  attribute_id?: ResourceId
  /** The attribute this value belongs to. */
  attribute?: Attribute
}
