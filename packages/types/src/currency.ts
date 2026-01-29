/**
 * Currency model.
 */
export interface Currency {
  id: number
  /** The name of the currency. */
  name: string
  /** The code of the currency (e.g., USD, EUR). */
  code: string
  /** The symbol of the currency (e.g., $, €). */
  symbol: string
  /** The format pattern for displaying amounts. */
  format: string
  /** The exchange rate relative to the base currency. */
  exchange_rate: number
  /** Whether the currency is enabled. */
  is_enabled: boolean
}
