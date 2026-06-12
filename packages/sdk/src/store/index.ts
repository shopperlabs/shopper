import type {
  Attribute,
  Brand,
  Category,
  Collection,
  Country,
  Currency,
  Product,
  Zone,
} from '@shopperlabs/shopper-types'

import type { HttpClient } from '../client'
import type { FetchRequestOptions } from '../http'
import { flatten } from '../json-api'
import { CartModule } from './cart'
import { CollectionResource } from './collection'
import { CustomerModule } from './customer'

export { CartModule } from './cart'
export type {
  CreateCartLinePayload,
  CreateCartPayload,
  ShippingOptionList,
  UpdateCartLinePayload,
} from './cart'
export { CollectionResource } from './collection'
export type { Paginated } from './collection'
export { CustomerModule } from './customer'

/**
 * Store API surface, mirroring the catalog and geo endpoints. Resources are
 * singular (sdk.store.product.list()) Geo resources
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
   * Low-level escape hatch: call any store endpoint by path and receive the
   * flattened payload. `path` is relative to the configured store prefix, so
   * the prefix is never hardcoded in calls. This is how addon routes
   * registered through shopper/http are consumed before (or instead of)
   * getting a dedicated SDK resource:
   *
   * ```ts
   * const { data } = await sdk.store.fetch<Post[]>('/posts', { page: { size: 10 } })
   * await sdk.store.fetch<Post>('/posts', { method: 'POST', body: { title: 'Hello' } })
   * ```
   *
   * The customer token, JSON:API headers and error handling apply exactly as
   * they do for the built-in resources.
   */
  public async fetch<T = unknown>(
    path: string,
    options?: FetchRequestOptions,
  ): Promise<{ data: T | T[] | null; meta?: Record<string, unknown>; links?: Record<string, unknown> }> {
    const { method = 'GET', body, ...params } = options ?? {}

    const document = await this.client.send(
      method,
      `/${this.client.storePrefix}/${path.replace(/^\//, '')}`,
      body,
      params,
    )

    if (! document) {
      return { data: null }
    }

    return { data: flatten<T>(document), meta: document.meta, links: document.links }
  }
}
