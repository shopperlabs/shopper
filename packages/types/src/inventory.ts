import type { Entity } from './common'
import type { Country } from './country'

/**
 * Inventory (warehouse/location) model.
 */
export interface Inventory extends Entity {
  /** The name of the inventory location. */
  name: string
  /** The code of the inventory location. */
  code: string
  /** The email of the inventory location. */
  email: string
  /** The city of the inventory location. */
  city: string
  /** The state/province of the inventory location. */
  state: string | null
  /** The description of the inventory location. */
  description: string | null
  /** The street address of the inventory location. */
  street_address: string | null
  /** The additional street address. */
  street_address_plus: string | null
  /** The postal code of the inventory location. */
  postal_code: string
  /** The phone number of the inventory location. */
  phone_number: string | null
  /** Whether this is the default inventory location. */
  is_default: boolean
  /** The priority of the inventory location. */
  priority: number
  /** The latitude of the inventory location. */
  latitude: number
  /** The longitude of the inventory location. */
  longitude: number
  /** The country ID of the inventory location. */
  country_id: number
  /** The country of the inventory location. */
  country?: Country
  /** The inventory histories. */
  histories?: InventoryHistory[]
}

/**
 * InventoryHistory model.
 */
export interface InventoryHistory extends Entity {
  /** The new quantity after the adjustment. */
  quantity: number
  /** The old quantity before the adjustment. */
  old_quantity: number | null
  /** The event that triggered this history entry. */
  event: string | null
  /** The description of the history entry. */
  description: string | null
  /** The user ID who made the adjustment. */
  user_id: number
  /** The inventory ID. */
  inventory_id: number
  /** The ID of the stockable entity. */
  stockable_id: number
  /** The type of the stockable entity. */
  stockable_type: string
  /** The ID of the reference entity. */
  reference_id?: number | null
  /** The type of the reference entity. */
  reference_type?: string | null
  /** The computed adjustment display. */
  adjustment?: string | number
  /** The inventory location. */
  inventory?: Inventory
}
