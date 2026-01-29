import type { Zone } from './zone'

/**
 * Country model.
 */
export interface Country {
  id: number
  /** The name of the country. */
  name: string
  /** The official name of the country. */
  name_official: string
  /** The region of the country. */
  region: string
  /** The subregion of the country. */
  subregion: string
  /** The ISO 3166-1 alpha-3 code. */
  cca3: string
  /** The ISO 3166-1 alpha-2 code. */
  cca2: string
  /** The flag emoji of the country. */
  flag: string
  /** The SVG flag URL of the country. */
  svg_flag: string
  /** The latitude of the country. */
  latitude: number
  /** The longitude of the country. */
  longitude: number
  /** The phone calling codes of the country. */
  phone_calling_code: Record<string, unknown>
  /** The currencies of the country. */
  currencies: Record<string, unknown>
  /** The zones this country belongs to. */
  zones?: Zone[]
}
