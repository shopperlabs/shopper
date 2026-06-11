import type { Attribute, Brand, Category, Collection, Country, Currency, Product, Zone } from '@shopperlabs/shopper-types'

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

/**
 * Store API surface, mirroring the catalog and geo endpoints. Resources are
 * singular (sdk.store.product.list()) like @medusajs/js-sdk. Geo resources
 * retrieve by code: sdk.store.country.retrieve('US'), sdk.store.zone.retrieve('eu'),
 * sdk.store.currency.retrieve('EUR'). Use `fetch()` for endpoints not yet
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
