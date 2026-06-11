import type {
  Address,
  Attribute,
  Brand,
  Cart,
  Category,
  Collection,
  Country,
  Currency,
  Customer,
  Order,
  Product,
  Zone,
} from '@shopperlabs/shopper-types'

import type { HttpClient } from './client'
import type { FetchOptions, RequestParams } from './http'
import { flatten } from './json-api'

/** A paginated list result: flattened entities plus JSON:API meta and links. */
export interface Paginated<T> {
  data: T[]
  meta?: Record<string, unknown>
  links?: Record<string, unknown>
}

class CollectionResource<T> {
  public constructor(
    private readonly client: HttpClient,
    private readonly path: string,
  ) {}

  public async list(params?: RequestParams): Promise<Paginated<T>> {
    const document = await this.client.request(this.path, params)

    return { data: (flatten<T>(document) ?? []) as T[], meta: document.meta, links: document.links }
  }

  /** Retrieve a single entity by its public identifier (slug for catalog, code for geo). */
  public async retrieve(identifier: string, params?: RequestParams): Promise<T> {
    return flatten<T>(await this.client.request(`${this.path}/${identifier}`, params)) as T
  }
}

class CustomerAddressResource {
  public constructor(
    private readonly client: HttpClient,
    private readonly path: string,
  ) {}

  public async list(params?: RequestParams): Promise<Address[]> {
    return (flatten<Address>(await this.client.request(this.path, params)) ?? []) as Address[]
  }

  public async create(payload: Record<string, unknown>): Promise<Address> {
    const document = await this.client.send('POST', this.path, payload)

    return flatten<Address>(document as NonNullable<typeof document>) as Address
  }

  public async update(id: string, payload: Record<string, unknown>): Promise<Address> {
    const document = await this.client.send('PATCH', `${this.path}/${id}`, payload)

    return flatten<Address>(document as NonNullable<typeof document>) as Address
  }

  public async delete(id: string): Promise<void> {
    await this.client.send('DELETE', `${this.path}/${id}`)
  }
}

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

  private params(params?: RequestParams): RequestParams {
    return { include: ['lines'], ...params }
  }
}

/**
 * Authenticated customer account: profile, addresses and orders.
 * Every call requires a token set by sdk.auth.login() or sdk.auth.register().
 */
export class CustomerModule {
  public readonly address: CustomerAddressResource

  public readonly order: Pick<CollectionResource<Order>, 'list'>

  private readonly path: string

  public constructor(private readonly client: HttpClient) {
    this.path = `/${client.storePrefix}/customers/me`
    this.address = new CustomerAddressResource(client, `${this.path}/addresses`)
    this.order = new CollectionResource<Order>(client, `${this.path}/orders`)
  }

  public async me(params?: RequestParams): Promise<Customer> {
    return flatten<Customer>(await this.client.request(this.path, params)) as Customer
  }

  public async update(payload: Record<string, unknown>): Promise<Customer> {
    const document = await this.client.send('PATCH', this.path, payload)

    return flatten<Customer>(document as NonNullable<typeof document>) as Customer
  }

  /** Upload a profile photo (image, max 1 MB). Returns the customer with the new avatar URL. */
  public async updateAvatar(file: Blob, filename?: string): Promise<Customer> {
    const form = new FormData()
    form.append('avatar', file, filename ?? 'avatar')

    const document = await this.client.send('POST', `${this.path}/avatar`, form)

    return flatten<Customer>(document as NonNullable<typeof document>) as Customer
  }

  /** Remove the uploaded photo and fall back to the generated avatar. */
  public async removeAvatar(): Promise<Customer> {
    const document = await this.client.send('DELETE', `${this.path}/avatar`)

    return flatten<Customer>(document as NonNullable<typeof document>) as Customer
  }
}

/**
 * Store API surface, mirroring the catalog and geo endpoints. Resources are
 * singular (sdk.store.product.list()) like @medusajs/js-sdk. Geo resources
 * retrieve by code: sdk.store.country.retrieve('CM'), sdk.store.zone.retrieve('af'),
 * sdk.store.currency.retrieve('XAF'). Use `fetch()` for endpoints not yet
 * wrapped, including addon routes registered through shopper/http.
 */
export class StoreModule {
  public readonly product: CollectionResource<Product>

  public readonly category: CollectionResource<Category>

  public readonly collection: CollectionResource<Collection>

  public readonly brand: CollectionResource<Brand>

  public readonly attribute: Pick<CollectionResource<Attribute>, 'list'>

  public readonly country: CollectionResource<Country>

  public readonly zone: CollectionResource<Zone>

  public readonly currency: CollectionResource<Currency>

  public readonly customer: CustomerModule

  public readonly cart: CartModule

  public constructor(private readonly client: HttpClient) {
    const prefix = `/${client.storePrefix}`

    this.product = new CollectionResource<Product>(client, `${prefix}/products`)
    this.category = new CollectionResource<Category>(client, `${prefix}/categories`)
    this.collection = new CollectionResource<Collection>(client, `${prefix}/collections`)
    this.brand = new CollectionResource<Brand>(client, `${prefix}/brands`)
    this.attribute = new CollectionResource<Attribute>(client, `${prefix}/attributes`)
    this.country = new CollectionResource<Country>(client, `${prefix}/countries`)
    this.zone = new CollectionResource<Zone>(client, `${prefix}/zones`)
    this.currency = new CollectionResource<Currency>(client, `${prefix}/currencies`)
    this.customer = new CustomerModule(client)
    this.cart = new CartModule(client)
  }

  /**
   * Low-level escape hatch: call any endpoint by path and receive the flattened
   * payload. `path` is relative to the base URL (e.g. "/store/best-sellers").
   */
  public async fetch<T = unknown>(
    path: string,
    options?: FetchOptions,
  ): Promise<{ data: T | T[] | null; meta?: Record<string, unknown>; links?: Record<string, unknown> }> {
    const document = await this.client.request(path, options)

    return { data: flatten<T>(document), meta: document.meta, links: document.links }
  }
}
