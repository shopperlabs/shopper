import type { Attribute, Brand, Category, Collection, Product } from '@shopperlabs/shopper-types'

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

  public async retrieve(slug: string, params?: RequestParams): Promise<T> {
    return flatten<T>(await this.client.request(`${this.path}/${slug}`, params)) as T
  }
}

/**
 * Store API surface, mirroring the catalog endpoints. Resources are singular
 * (sdk.store.product.list()) like @medusajs/js-sdk. Use `fetch()` for endpoints
 * not yet wrapped, including addon routes registered through shopper/http.
 */
export class StoreModule {
  public readonly product: CollectionResource<Product>

  public readonly category: CollectionResource<Category>

  public readonly collection: CollectionResource<Collection>

  public readonly brand: CollectionResource<Brand>

  public readonly attribute: Pick<CollectionResource<Attribute>, 'list'>

  public constructor(private readonly client: HttpClient) {
    const prefix = `/${client.storePrefix}`

    this.product = new CollectionResource<Product>(client, `${prefix}/products`)
    this.category = new CollectionResource<Category>(client, `${prefix}/categories`)
    this.collection = new CollectionResource<Collection>(client, `${prefix}/collections`)
    this.brand = new CollectionResource<Brand>(client, `${prefix}/brands`)
    this.attribute = new CollectionResource<Attribute>(client, `${prefix}/attributes`)
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
