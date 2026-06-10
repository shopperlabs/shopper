import { buildQuery, type FetchOptions, toApiError } from './http'
import type { JsonApiDocument } from './json-api'

export interface ShopperConfig {
  /** Base URL of the Shopper application, e.g. https://my-store.com */
  baseUrl: string
  /** Store route prefix used by the resource accessors. Defaults to "store". */
  storePrefix?: string
  /** Extra headers merged into every request (auth tokens, locale, ...). */
  headers?: Record<string, string>
  /** Custom fetch implementation (defaults to the global fetch). */
  fetch?: typeof globalThis.fetch
}

/**
 * Thin transport over fetch: builds the URL, forces the JSON:API Accept header,
 * and turns non-2xx responses into a ShopperApiError. Returns the raw JSON:API
 * document; denormalization happens in the resources.
 */
export class HttpClient {
  public readonly storePrefix: string

  private readonly baseUrl: string

  private readonly headers: Record<string, string>

  private readonly fetchImpl: typeof globalThis.fetch

  public constructor(config: ShopperConfig) {
    this.baseUrl = config.baseUrl.replace(/\/$/, '')
    this.storePrefix = (config.storePrefix ?? 'store').replace(/^\/|\/$/g, '')
    this.headers = config.headers ?? {}
    // Bind to the global so the browser fetch keeps `this === window`
    this.fetchImpl = (config.fetch ?? globalThis.fetch).bind(globalThis)
  }

  public async request(path: string, options?: FetchOptions): Promise<JsonApiDocument> {
    const url = `${this.baseUrl}/${path.replace(/^\//, '')}${buildQuery(options)}`

    const response = await this.fetchImpl(url, {
      method: 'GET',
      headers: { Accept: 'application/vnd.api+json', ...this.headers },
    })

    if (! response.ok) {
      throw await toApiError(response)
    }

    return (await response.json()) as JsonApiDocument
  }
}
