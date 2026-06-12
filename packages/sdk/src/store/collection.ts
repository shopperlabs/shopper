import type { HttpClient } from '../client'
import type { RequestParams } from '../http'
import { flatten } from '../json-api'

/** A paginated list result: flattened entities plus JSON:API meta and links. */
export interface Paginated<T> {
  data: T[]
  meta?: Record<string, unknown>
  links?: Record<string, unknown>
}

/**
 * Read-only resource shared by the catalog and geo endpoints: a list and a
 * retrieve by public identifier (slug for catalog, code for geo).
 */
export class CollectionResource<T> {
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
