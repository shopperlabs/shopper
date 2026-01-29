import type { Carrier, CarrierOption } from './carrier'
import type { Collection } from './collection'
import type { Entity, Metadata } from './common'
import type { Country } from './country'
import type { Currency } from './currency'
import type { PaymentMethod } from './payment_method'

/**
 * Zone model.
 */
export interface Zone extends Entity {
  /** The name of the zone. */
  name: string
  /** The slug of the zone. */
  slug: string
  /** The code of the zone. */
  code: string | null
  /** Whether the zone is enabled. */
  is_enabled: boolean
  /** The currency ID of the zone. */
  currency_id: number | null
  /** The metadata of the zone. */
  metadata: Metadata
  /** Computed: carriers names joined. */
  carriers_name?: string
  /** Computed: countries names joined. */
  countries_name?: string
  /** Computed: payment methods names joined. */
  payments_name?: string
  /** Computed: currency code. */
  currency_code?: string
  /** The currency of the zone. */
  currency?: Currency
  /** The carriers in this zone. */
  carriers?: Carrier[]
  /** The shipping options in this zone. */
  shippingOptions?: CarrierOption[]
  /** The payment methods in this zone. */
  paymentMethods?: PaymentMethod[]
  /** The countries in this zone. */
  countries?: Country[]
  /** The collections in this zone. */
  collections?: Collection[]
}
