import type { HttpClient } from '../client'
import type { FetchInit, RequestParams } from '../http'
import { flatten, type Flattened } from '../json-api'

/**
 * Read-only resource for endpoints that answer with one entity rather than a
 * collection, such as the store settings. No pagination, no identifier: there
 * is only ever one of them.
 *
 * Responses are cacheable, so caching belongs to the transport rather than to
 * this class: pass `init` through to the runtime that owns it, for instance
 * `{ next: { revalidate: 300, tags: ['settings'] } }` on Next.js.
 */
export class SingletonResource<T> {
  public constructor(
    protected readonly client: HttpClient,
    protected readonly path: string,
  ) {}

  public async retrieve(params?: RequestParams, init?: FetchInit): Promise<Flattened<T>> {
    return flatten<T>(await this.client.request(this.path, params, init)) as Flattened<T>
  }

  /** Read a single key, for the many places a page needs just one value. */
  public async get<K extends keyof Flattened<T>>(key: K, init?: FetchInit): Promise<Flattened<T>[K]> {
    return (await this.retrieve(undefined, init))[key]
  }
}
