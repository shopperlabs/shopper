import type { JsonApiErrorDocument } from './json-api'

/**
 * Structured JSON:API query parameters understood by every list/retrieve call,
 * mapped to the query family the Shopper API expects (filter[], sort, include,
 * page[number], page[size], page[cursor], fields[type]).
 */
export interface RequestParams {
  include?: string[]
  filter?: Record<string, string | number | boolean>
  sort?: string[]
  page?: { number?: number; size?: number; cursor?: string }
  fields?: Record<string, string[]>
}

/** Options for the low-level `store.fetch()` escape hatch: structured params plus arbitrary query values. */
export interface FetchOptions extends RequestParams {
  query?: Record<string, string | number | boolean>
}

/** Full request options for `store.fetch()`: query params plus method and body for write calls. */
export interface FetchRequestOptions extends FetchOptions {
  /** HTTP method. Defaults to GET. */
  method?: 'GET' | 'POST' | 'PATCH' | 'DELETE'
  /** JSON payload, or a FormData for file uploads. */
  body?: Record<string, unknown> | FormData
}

export function buildQuery(options?: FetchOptions): string {
  if (! options) {
    return ''
  }

  const search = new URLSearchParams()

  if (options.include?.length) {
    search.set('include', options.include.join(','))
  }

  if (options.sort?.length) {
    search.set('sort', options.sort.join(','))
  }

  for (const [field, value] of Object.entries(options.filter ?? {})) {
    search.set(`filter[${field}]`, String(value))
  }

  if (options.page?.number) {
    search.set('page[number]', String(options.page.number))
  }

  if (options.page?.size) {
    search.set('page[size]', String(options.page.size))
  }

  if (options.page?.cursor !== undefined) {
    search.set('page[cursor]', options.page.cursor)
  }

  for (const [type, list] of Object.entries(options.fields ?? {})) {
    search.set(`fields[${type}]`, list.join(','))
  }

  for (const [key, value] of Object.entries(options.query ?? {})) {
    search.set(key, String(value))
  }

  const query = search.toString()

  return query ? `?${query}` : ''
}

/**
 * Error thrown when the API returns a non-2xx response. Carries the HTTP status
 * and the parsed JSON:API error objects when available.
 */
export class ShopperApiError extends Error {
  public readonly status: number

  public readonly errors: JsonApiErrorDocument['errors']

  public constructor(status: number, errors: JsonApiErrorDocument['errors']) {
    super(errors[0]?.detail ?? errors[0]?.title ?? `Request failed with status ${status}`)
    this.name = 'ShopperApiError'
    this.status = status
    this.errors = errors
  }
}

export async function toApiError(response: Response): Promise<ShopperApiError> {
  let errors: JsonApiErrorDocument['errors'] = []

  try {
    const body = (await response.json()) as Partial<JsonApiErrorDocument>
    errors = body.errors ?? []
  } catch {
    errors = []
  }

  return new ShopperApiError(response.status, errors)
}
