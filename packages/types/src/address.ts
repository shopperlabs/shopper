import type { Entity, Metadata } from './common'
import type { Country } from './country'

export enum AddressType {
  BILLING = 'billing',
  SHIPPING = 'shipping',
}

/**
 * The Address interface
 */
export interface Address extends Entity {
  /** The first name of the address. */
  first_name: string | null
  /** The last name of the address. */
  last_name: string
  /** The full name (first_name + last_name). */
  full_name: string
  /** The company name of the address. */
  company_name: string | null
  /** The street address. */
  street_address: string
  /** The additional street address. */
  street_address_plus?: string | null
  /** The postal code. */
  postal_code: string
  /** The city. */
  city: string
  /** The state/province. */
  state: string | null
  /** The phone number. */
  phone_number?: string | null
  /** The type of the customer address. */
  type: AddressType
  /** The metadata of the address. */
  metadata: Metadata
  /** Whether this is the default shipping address. */
  shipping_default: boolean
  /** Whether this is the default billing address. */
  billing_default: boolean
  /** ID of the user this address belongs to. */
  user_id: number
  /** ID of the country this address belongs to. */
  country_id: number
  /** The country of the address. */
  country?: Country
}
