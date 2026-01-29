import type { Entity } from './common'

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
  type: FieldType
  /** The icon of the attribute. */
  icon: string | null
  /** The description of the attribute. */
  description: string | null
  /** Whether the attribute is enabled. */
  is_enabled: boolean
  /** Whether the attribute is searchable. */
  is_searchable: boolean
  /** Whether the attribute is filterable. */
  is_filterable: boolean
  /** The computed formatted type. */
  type_formatted?: string
  /** The values of the attribute. */
  values?: AttributeValue[]
}

/**
 * AttributeValue model.
 */
export interface AttributeValue {
  id: number
  /** The display value. */
  value: string
  /** The key identifier. */
  key: string
  /** The position/order. */
  position: number
  /** The attribute ID this value belongs to. */
  attribute_id: number
  /** The attribute this value belongs to. */
  attribute?: Attribute
}
