import type { Zone } from './zone'

/**
 * Check if a currency code does not use subunits (e.g., JPY, KRW).
 */
export function isNoDivisionCurrency(currency: string): boolean {
  return [
    'BIF', 'CLP', 'DJF', 'GNF', 'HTG', 'JPY', 'KMF', 'KRW', 'MGA',
    'PYG', 'RWF', 'VND', 'VUV', 'XAF', 'XAG', 'XAU', 'XDR', 'XOF', 'XPF',
  ].includes(currency)
}

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
  /** The zone using this currency. */
  zone?: Zone
}
