/**
 * A delivery choice computed for a cart by `GET /store/carts/{id}/shipping-options`.
 *
 * Not a stored entity: flat options come from the merchant's zone rates,
 * calculated ones from live carrier APIs (UPS, FedEx, ...). The `id` is the
 * composite `{carrier_code}:{service_code}` the checkout sends back to pick
 * a method; the server re-resolves the price from it, so `amount` is
 * advisory display data.
 */
export interface ShippingOption {
  /** Composite identifier `{carrier_code}:{service_code}`. */
  id: string
  /** The display name of the service (e.g. "Standard", "UPS Ground"). */
  name: string
  /** The display name of the carrier. */
  carrier_name: string
  /** The carrier identifier (slug). */
  carrier_code: string
  /** The service identifier within the carrier. */
  service_code: string
  /** The amount (in cents), advisory until checkout re-prices it. */
  amount: number
  /** The ISO currency code, always the cart currency. */
  currency: string
  /** `flat` for merchant zone rates, `calculated` for live carrier quotes. */
  price_type: 'flat' | 'calculated'
  /** The estimated delivery delay in days, when the carrier provides it. */
  estimated_days: string | null
  /** The ISO 8601 estimated delivery date, when the carrier provides it. */
  estimated_delivery: string | null
}
