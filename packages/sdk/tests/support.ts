import type { JsonApiDocument, JsonApiErrorDocument } from '../src/json-api'

export interface Call {
  url: string
  init: RequestInit
}

export function fakeFetch(responses: Array<() => Response>): { fetch: typeof globalThis.fetch; calls: Call[] } {
  const calls: Call[] = []
  const queue = [...responses]

  const fetch = (async (input: RequestInfo | URL, init?: RequestInit): Promise<Response> => {
    calls.push({ url: String(input), init: init ?? {} })

    const next = queue.shift()

    if (next === undefined) {
      throw new Error('No fake response left.')
    }

    return next()
  }) as typeof globalThis.fetch

  return { fetch, calls }
}

export function json(status: number, body: JsonApiDocument | JsonApiErrorDocument | Record<string, unknown>): () => Response {
  return () =>
    status === 204
      ? new Response(null, { status })
      : new Response(JSON.stringify(body), { status, headers: { 'Content-Type': 'application/vnd.api+json' } })
}
