import type { Country } from './country'
import type { Entity, Metadata } from './common'

/**
 * TaxProvider model.
 */
export interface TaxProvider extends Entity {
  /** The provider identifier (e.g., "system", "stripe_tax"). */
  identifier: string
  /** Whether the provider is enabled. */
  is_enabled: boolean
  /** The tax zones using this provider. */
  taxZones?: TaxZone[]
}

/**
 * TaxZone model.
 */
export interface TaxZone extends Entity {
  /** The zone name. */
  name: string | null
  /** The country ID. */
  country_id: number
  /** The province code (for sub-country zones). */
  province_code: string | null
  /** Whether prices in this zone include tax. */
  is_tax_inclusive: boolean
  /** The parent zone ID. */
  parent_id: number | null
  /** The tax provider ID. */
  provider_id: number | null
  /** The metadata. */
  metadata: Metadata
  /** The computed display name (country — name). */
  display_name: string
  /** The country. */
  country?: Country
  /** The parent zone. */
  parent?: TaxZone | null
  /** The child zones. */
  children?: TaxZone[]
  /** The tax rates for this zone. */
  rates?: TaxRate[]
  /** The tax provider. */
  provider?: TaxProvider | null
}

/**
 * TaxRate model.
 */
export interface TaxRate extends Entity {
  /** The rate name (e.g., "TVA", "Sales Tax"). */
  name: string
  /** The rate code (e.g., "CM-TVA-20"). */
  code: string | null
  /** The tax rate percentage (e.g., 20.0 for 20%). */
  rate: number
  /** Whether this is the default rate for the zone. */
  is_default: boolean
  /** Whether this rate can be combined with others. */
  is_combinable: boolean
  /** The tax zone ID. */
  tax_zone_id: number
  /** The metadata. */
  metadata: Metadata
  /** The tax zone. */
  taxZone?: TaxZone
  /** The rules for this rate. */
  rules?: TaxRateRule[]
}

/**
 * TaxRateRule model.
 */
export interface TaxRateRule extends Entity {
  /** The morph type of the reference (e.g., product type, category). */
  reference_type: string
  /** The morph ID of the reference. */
  reference_id: string
  /** The tax rate ID. */
  tax_rate_id: number
  /** The tax rate. */
  taxRate?: TaxRate
}

/**
 * OrderTaxLine model — snapshot tax applied to an order item or shipping.
 */
export interface OrderTaxLine extends Entity {
  /** The morph type (OrderItem or OrderShipping). */
  taxable_type: string
  /** The morph ID. */
  taxable_id: number
  /** The snapshot tax code (e.g., "VAT", "GST"). */
  code: string
  /** The snapshot tax name (e.g., "TVA 20%"). */
  name: string
  /** The snapshot tax rate percentage. */
  rate: number
  /** The calculated tax amount (in cents). */
  amount: number
  /** Optional reference to the source tax rate. */
  tax_rate_id: number | null
  /** The source tax rate (if still exists). */
  taxRate?: TaxRate | null
}
