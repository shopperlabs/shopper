import { buildQuery, type FetchInit, type FetchOptions, toApiError } from './http'
import type { JsonApiDocument } from './json-api'

/**
 * Where the customer token lives between requests. Defaults to in-memory;
 * provide a custom implementation (localStorage, cookies, ...) to persist
 * the session across page loads.
 */
export interface TokenStorage {
  get(): string | null
  set(token: string): void
  clear(): void
}

class MemoryTokenStorage implements TokenStorage {
  private token: string | null = null

  public get(): string | null {
    return this.token
  }

  public set(token: string): void {
    this.token = token
  }

  public clear(): void {
    this.token = null
  }
}

export interface ShopperConfig {
  /** Base URL of the Shopper application, e.g. https://my-store.com */
  baseUrl: string
  /** Store route prefix used by the resource accessors. Defaults to "store". */
  storePrefix?: string
  /**
   * Locale sent as the Accept-Language header on every request. The API
   * negotiates it against the locales the shop supports and answers with
   * translated content (country names, translatable addons, ...).
   */
  locale?: string
  /** Extra headers merged into every request (auth tokens, locale, ...). */
  headers?: Record<string, string>
  /** Custom fetch implementation (defaults to the global fetch). */
  fetch?: typeof globalThis.fetch
  /** Customer token persistence. Defaults to an in-memory store. */
  tokenStorage?: TokenStorage
}

/**
 * Thin transport over fetch: builds the URL, forces the JSON:API Accept header,
 * attaches the customer token when one is set, and turns non-2xx responses into
 * a ShopperApiError. Returns the raw JSON:API document; denormalization happens
 * in the resources.
 */
export class HttpClient {
  public readonly storePrefix: string

  public readonly tokens: TokenStorage

  private readonly baseUrl: string

  private readonly headers: Record<string, string>

  private readonly fetchImpl: typeof globalThis.fetch

  private locale: string | null

  public constructor(config: ShopperConfig) {
    this.baseUrl = config.baseUrl.replace(/\/$/, '')
    this.storePrefix = (config.storePrefix ?? 'store').replace(/^\/|\/$/g, '')
    this.locale = config.locale ?? null
    this.headers = config.headers ?? {}
    this.tokens = config.tokenStorage ?? new MemoryTokenStorage()
    // Bind to the global so the browser fetch keeps `this === window`
    this.fetchImpl = (config.fetch ?? globalThis.fetch).bind(globalThis)
  }

  /** Switch the language of subsequent calls; null reverts to the shop default. */
  public setLocale(locale: string | null): void {
    this.locale = locale
  }

  public getLocale(): string | null {
    return this.locale
  }

  public async request(path: string, options?: FetchOptions, init?: FetchInit): Promise<JsonApiDocument> {
    return (await this.send('GET', path, undefined, options, init)) as JsonApiDocument
  }

  /**
   * Perform a write request. Accepts a JSON payload or a FormData (file
   * uploads, the boundary header is left to fetch). Returns the parsed
   * JSON:API document, or null for bodyless responses (204, 202).
   */
  public async send(
    method: 'GET' | 'POST' | 'PATCH' | 'DELETE',
    path: string,
    body?: Record<string, unknown> | FormData,
    options?: FetchOptions,
    init?: FetchInit,
  ): Promise<JsonApiDocument | null> {
    const url = `${this.baseUrl}/${path.replace(/^\//, '')}${buildQuery(options)}`
    const token = this.tokens.get()
    const isForm = typeof FormData !== 'undefined' && body instanceof FormData
    const { headers: initHeaders, ...initRest } = init ?? {}

    const response = await this.fetchImpl(url, {
      ...initRest,
      method,
      headers: {
        Accept: 'application/vnd.api+json',
        ...(body !== undefined && ! isForm ? { 'Content-Type': 'application/json' } : {}),
        ...(token !== null ? { Authorization: `Bearer ${token}` } : {}),
        ...(this.locale !== null ? { 'Accept-Language': this.locale } : {}),
        ...this.headers,
        ...initHeaders,
      },
      ...(body !== undefined ? { body: isForm ? (body as FormData) : JSON.stringify(body) } : {}),
    })

    if (! response.ok) {
      throw await toApiError(response)
    }

    if (response.status === 204 || response.status === 202) {
      return null
    }

    const text = await response.text()

    return text === '' ? null : (JSON.parse(text) as JsonApiDocument)
  }
}
