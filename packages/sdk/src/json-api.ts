/**
 * JSON:API document handling.
 *
 * The Shopper API serves normalized JSON:API documents: a resource exposes its
 * own attributes plus relationship linkage ({ type, id }), and the full related
 * resources live in the top-level `included` array when requested via `include`.
 *
 * `flatten()` denormalizes that document back into the plain, nested shapes
 * declared by `@shopperlabs/shopper-types`, so consumers never deal with the
 * wire format: `product.variants` is an array of full variants, not pointers.
 */

export interface ResourceIdentifier {
  type: string
  id: string
}

export interface JsonApiResource extends ResourceIdentifier {
  attributes?: Record<string, unknown>
  relationships?: Record<string, { data: ResourceIdentifier | ResourceIdentifier[] | null }>
}

export interface JsonApiDocument {
  data: JsonApiResource | JsonApiResource[] | null
  included?: JsonApiResource[]
  meta?: Record<string, unknown>
  links?: Record<string, unknown>
}

export interface JsonApiError {
  status?: string
  /** Stable, untranslated machine code: the failed validation rule (unique, required, ...) or a domain code (credentials_invalid, stock_insufficient, ...). */
  code?: string
  title?: string
  detail?: string
  source?: { pointer?: string; parameter?: string }
  /** Structured details of a domain error, such as the rule behind `quantity_rule_violated`. */
  meta?: Record<string, unknown>
}

export interface JsonApiErrorDocument {
  errors: JsonApiError[]
}

/**
 * The shape flatten() returns: JSON:API ids are always strings, so the `id` of
 * the entity and of its related entities, a `ResourceId` in the base type, is
 * typed `string`. Objects without an `id` (dates, metadata) stay as they are, so
 * the result remains assignable to its base type.
 */
export type Flattened<T> = T extends (infer U)[]
  ? Flattened<U>[]
  : 'id' extends keyof T
    ? { [K in keyof T]: K extends 'id' ? string : Flattened<T[K]> }
    : T

function identifier(resource: ResourceIdentifier): string {
  return `${resource.type}:${resource.id}`
}

/**
 * Denormalize a JSON:API document into flat entities. Returns an array when the
 * document's primary data is a collection, a single object for a single resource,
 * or null. Relationships present in `included` are inlined as nested objects;
 * relationships that are linkage-only resolve to a minimal `{ id }` stub.
 */
export function flatten<T = unknown>(document: JsonApiDocument): T | T[] | null {
  const index = new Map<string, JsonApiResource>()

  for (const resource of document.included ?? []) {
    index.set(identifier(resource), resource)
  }

  if (Array.isArray(document.data)) {
    return document.data.map((resource) => resolve(resource, index, new Map())) as T[]
  }

  if (document.data == null) {
    return null
  }

  return resolve(document.data, index, new Map()) as T
}

function resolve(
  resource: JsonApiResource,
  index: Map<string, JsonApiResource>,
  seen: Map<string, Record<string, unknown>>,
): Record<string, unknown> {
  const key = identifier(resource)
  const existing = seen.get(key)

  if (existing) {
    return existing
  }

  const flat: Record<string, unknown> = { id: resource.id, ...(resource.attributes ?? {}) }
  seen.set(key, flat)

  for (const [name, relationship] of Object.entries(resource.relationships ?? {})) {
    const data = relationship.data

    if (data == null) {
      flat[name] = null
      continue
    }

    flat[name] = Array.isArray(data)
      ? data.map((reference) => resolveReference(reference, index, seen))
      : resolveReference(data, index, seen)
  }

  return flat
}

function resolveReference(
  reference: ResourceIdentifier,
  index: Map<string, JsonApiResource>,
  seen: Map<string, Record<string, unknown>>,
): Record<string, unknown> {
  const included = index.get(identifier(reference))

  return included ? resolve(included, index, seen) : { id: reference.id }
}
