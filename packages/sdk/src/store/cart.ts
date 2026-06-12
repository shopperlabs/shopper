import type { Cart, ShippingOption } from '@shopperlabs/shopper-types'

import type { HttpClient } from '../client'
import type { RequestParams } from '../http'
import { flatten } from '../json-api'

export type CreateCartPayload = {
  /** ISO currency code the cart prices in. Defaults to the zone or shop currency. */
  currency_code?: string
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

  private params(params?: RequestParams): RequestParams {
    return { include: ['lines'], ...params }
  }
}
