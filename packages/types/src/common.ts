import type { Currency } from './currency'

export enum Weight {
  KG = 'kg',
  G = 'g',
  LBS = 'lbs',
}

export enum Length {
  M = 'm',
  CM = 'cm',
  MM = 'mm',
  FT = 'ft',
  IN = 'in',
}

export enum Volume {
  L = 'l',
  ML = 'ml',
  GAL = 'gal',
  FLOZ = 'floz',
}

/**
 * Global entity for all the models.
 */
export interface Entity {
  /** The internal id of the entity (admin). */
  id: string | number
  /** The stable public identifier (ULID) exposed by the API. */
  public_id?: string
  /** The ISO 8601 created at timestamp. */
  created_at?: string
  /** The ISO 8601 updated at timestamp. */
  updated_at?: string
  /** The ISO 8601 deleted at timestamp. */
  deleted_at?: string | null
}

/**
 * Price interface for entity.
 */
export interface Price {
  /** The internal id of the entity (admin). */
  id?: string | number
  /** The stable public identifier (ULID) exposed by the API. */
  public_id?: string
  /** The original amount for the entity. */
  amount: number | null
  /** The compare_amount amount for the entity. */
  compare_amount: number | null
  /** The cost_amount for the entity. */
  cost_amount?: number | null
  /** The currency_id for the entity. */
  currency_id?: number
  /** The currency_code for the entity. */
  currency_code: string
  /** The currency for the entity. */
  currency?: Currency
}

/**
 * ShippingFields interface for shipping entity.
 */
export interface ShippingFields {
  /** The width_unit of the entity. */
  width_unit: Length
  /** The width_value of the entity. */
  width_value: number | null
  /** The weight_unit of the entity. */
  weight_unit: Weight
  /** The weight_value of the entity. */
  weight_value: number | null
  /** The height_unit of the entity. */
  height_unit: Length
  /** The height_value of the entity. */
  height_value: number | null
  /** The depth_unit of the entity. */
  depth_unit: Length
  /** The depth_value of the entity. */
  depth_value: number | null
  /** The volume_unit of the entity. */
  volume_unit: Volume
  /** The volume_value of the entity. */
  volume_value: number | null
}

/**
 * Seo Fields interface for entities.
 */
export interface SEOFields {
  seo_title?: string | null
  seo_description?: string | null
}

/**
 * Metadata type for entities.
 */
export type Metadata = Record<string, unknown> | null
