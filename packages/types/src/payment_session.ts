/**
 * A payment session opened for a cart
 *
 * Not a stored entity: it mirrors what the payment driver initiated at the
 * provider (a Stripe payment intent, ...). The `id` is the driver reference
 * the storefront confirms against; `data` carries driver-specific extras
 * such as the publishable key.
 */
export interface PaymentSession {
  /** The driver reference of the session (e.g. a Stripe payment intent id). */
  id: string
  /** The payment driver that opened the session (e.g. "stripe", "manual"). */
  driver: string
  /** The driver-reported status of the session. */
  status: string
  /** The amount to collect, in cents. */
  amount: number | null
  /** The ISO currency code the amount is in. */
  currency_code: string
  /** The secret the storefront uses to confirm the payment client-side. */
  client_secret: string | null
  /** The URL to redirect the customer to, for redirect-based drivers. */
  redirect_url: string | null
  /** Driver-specific extras (publishable key, ...). */
  data: Record<string, unknown>
}
