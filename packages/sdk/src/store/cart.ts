import type { Cart, Order, PaymentMethod, PaymentSession, ShippingOption } from '@shopperlabs/shopper-types'

import type { HttpClient } from '../client'
import type { RequestParams } from '../http'
import { flatten } from '../json-api'

export type CreateCartPayload = {
  /** ISO currency code the cart prices in. Defaults to the zone or shop currency. */
  currency_code?: string
  /** Contact email frozen on the order at completion. Required for a guest cart, set here or with the checkout addresses. */
  email?: string
  metadata?: Record<string, unknown> | null
}

export type UpdateCartPayload = {
  /** Re-price the cart in another currency. Drops the shipping and payment choices bound to the old one. */
  currency_code?: string
  /** Contact email frozen on the order at completion. */
  email?: string | null
  metadata?: Record<string, unknown> | null
}

export type CreateCartLinePayload = {
  /** Public id of the product or variant to add. */
  purchasable_id: string
  purchasable_type: 'product' | 'variant'
  /** Defaults to 1. Adding the same purchasable again increments the existing line. */
  quantity?: number
  metadata?: Record<string, unknown> | null
}

export type UpdateCartLinePayload = {
  quantity?: number
  metadata?: Record<string, unknown> | null
}

export type CartAddressPayload = {
  first_name?: string | null
  last_name: string
  company?: string | null
  address_1: string
  address_2?: string | null
  city: string
  state?: string | null
  postal_code: string
  phone?: string | null
  /** ISO 3166-1 alpha-2 country code. */
  country_code: string
}

export type SetCartAddressesPayload = {
  shipping_address?: CartAddressPayload
  billing_address?: CartAddressPayload
  /** Contact email frozen on the order at completion. Required for a guest cart when not set at creation. */
  email?: string
}

/** Delivery choices for a cart plus the non-fatal notices raised while quoting. */
export interface ShippingOptionList {
  data: ShippingOption[]
  /** Carriers that failed to quote, options dropped for currency mismatch, ... */
  warnings: string[]
}

/**
 * Guest and customer carts. A cart is addressed by its public id: persist it
 * (cookie, localStorage) to reuse the cart across visits. When a customer
 * token is set, carts created through create() belong to that customer and
 * are hidden from anyone else.
 *
 * Every call answers with the cart summary and its pipeline-computed totals.
 * The API keeps lines and addresses behind JSON:API includes; the SDK asks
 * for `lines` by default so `cart.lines` is always populated. Pass your own
 * `include` to change that: `[]` for the bare summary (header badge),
 * `['lines.purchasable']` to also expand products and variants,
 * `['addresses']` for the checkout addresses.
 */
export class CartModule {
  private readonly path: string

  public constructor(private readonly client: HttpClient) {
    this.path = `/${client.storePrefix}/carts`
  }

  public async create(payload: CreateCartPayload = {}, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send('POST', this.path, payload, this.params(params))

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  public async retrieve(id: string, params?: RequestParams): Promise<Cart> {
    return flatten<Cart>(await this.client.request(`${this.path}/${id}`, this.params(params))) as Cart
  }

  /**
   * Patch the cart currency, contact email or metadata. Switching the currency
   * re-prices the lines and drops the shipping and payment choices bound to the
   * old currency; it is rejected when a line has no price in the target.
   */
  public async update(id: string, payload: UpdateCartPayload, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send('PATCH', `${this.path}/${id}`, payload, this.params(params))

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  /**
   * Attach a guest cart to the authenticated customer, used when a guest signs
   * in mid-checkout. Requires a customer token. Idempotent for a cart they
   * already own; rejected for a cart that belongs to another customer.
   */
  public async transfer(cartId: string, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send('POST', `${this.path}/${cartId}/transfer`, undefined, this.params(params))

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  public async createLineItem(cartId: string, payload: CreateCartLinePayload, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send('POST', `${this.path}/${cartId}/lines`, payload, this.params(params))

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  public async updateLineItem(
    cartId: string,
    lineId: string,
    payload: UpdateCartLinePayload,
    params?: RequestParams,
  ): Promise<Cart> {
    const document = await this.client.send(
      'PATCH',
      `${this.path}/${cartId}/lines/${lineId}`,
      payload,
      this.params(params),
    )

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  public async deleteLineItem(cartId: string, lineId: string, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send(
      'DELETE',
      `${this.path}/${cartId}/lines/${lineId}`,
      undefined,
      this.params(params),
    )

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  /**
   * List the delivery choices for the cart. Flat zone rates show up as soon
   * as the cart exists; live carrier rates require a shipping address on the
   * cart. Send the chosen option id back when setting the shipping method:
   * the displayed amount is advisory and is re-priced at order placement.
   */
  public async shippingOptions(cartId: string, params?: RequestParams): Promise<ShippingOptionList> {
    const document = await this.client.request(`${this.path}/${cartId}/shipping-options`, params)

    return {
      data: (flatten<ShippingOption>(document) ?? []) as ShippingOption[],
      warnings: (document.meta?.warnings as string[] | undefined) ?? [],
    }
  }

  /**
   * Set the checkout addresses of the cart. Send `shipping_address`,
   * `billing_address` or both; each one replaces the address of its type.
   * The shipping address unlocks live carrier rates in shippingOptions().
   */
  public async setAddresses(cartId: string, payload: SetCartAddressesPayload, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send('POST', `${this.path}/${cartId}/addresses`, payload, this.params(params))

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  /**
   * Pick a delivery choice from the ids quoted by shippingOptions(). The
   * price is re-resolved server-side and folded into the cart totals.
   */
  public async setShippingMethod(cartId: string, optionId: string, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send(
      'POST',
      `${this.path}/${cartId}/shipping-method`,
      { option_id: optionId },
      this.params(params),
    )

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  /**
   * List the payment methods available for the cart: enabled methods with a
   * configured driver, restricted to the cart zone when it has one.
   */
  public async paymentMethods(cartId: string, params?: RequestParams): Promise<PaymentMethod[]> {
    const document = await this.client.request(`${this.path}/${cartId}/payment-methods`, params)

    return (flatten<PaymentMethod>(document) ?? []) as PaymentMethod[]
  }

  /** Set the payment method of the cart by the id listed in paymentMethods(). */
  public async setPaymentMethod(cartId: string, paymentMethodId: string, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send(
      'POST',
      `${this.path}/${cartId}/payment-method`,
      { payment_method_id: paymentMethodId },
      this.params(params),
    )

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  /**
   * Apply a promotion code to the cart. An unknown, expired or non applicable
   * code is rejected and the cart is left unchanged; on success the discount
   * is folded into the cart totals (`cart.discount_total`).
   */
  public async applyPromotion(cartId: string, code: string, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send(
      'POST',
      `${this.path}/${cartId}/promotion`,
      { code },
      this.params(params),
    )

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  /** Remove the promotion code applied to the cart. */
  public async removePromotion(cartId: string, params?: RequestParams): Promise<Cart> {
    const document = await this.client.send(
      'DELETE',
      `${this.path}/${cartId}/promotion`,
      undefined,
      this.params(params),
    )

    return flatten<Cart>(document as NonNullable<typeof document>) as Cart
  }

  /**
   * Open a payment session with the driver of the cart's payment method
   * (a Stripe payment intent, ...). The response carries what the storefront
   * needs to confirm the payment: client secret, publishable key, redirect
   * url. Calling it again while the total is unchanged resumes the same
   * session; after the cart moved it opens a fresh one.
   */
  public async createPaymentSession(cartId: string, params?: RequestParams): Promise<PaymentSession> {
    const document = await this.client.send('POST', `${this.path}/${cartId}/payment-session`, undefined, params)

    return flatten<PaymentSession>(document as NonNullable<typeof document>) as PaymentSession
  }

  /**
   * Place the order. The cart must carry a payment method, and a shipping
   * method when it holds shippable lines. Completion is idempotent: retrying
   * a timed-out call answers with the order already placed instead of
   * duplicating it. Persist the returned order id: it is the key to the
   * order confirmation lookup (sdk.store.order.retrieve()).
   */
  public async complete(cartId: string, params?: RequestParams): Promise<Order> {
    const document = await this.client.send('POST', `${this.path}/${cartId}/complete`, undefined, params)

    return flatten<Order>(document as NonNullable<typeof document>) as Order
  }

  private params(params?: RequestParams): RequestParams {
    return { include: ['lines'], ...params }
  }
}
