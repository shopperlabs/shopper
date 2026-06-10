import { HttpClient, type ShopperConfig } from './client'
import { StoreModule } from './store'

/**
 * Shopper SDK client.
 *
 * ```ts
 * import Shopper from '@shopperlabs/shopper-sdk'
 *
 * const sdk = new Shopper({ baseUrl: 'https://my-store.com' })
 * const { data } = await sdk.store.product.list({ include: ['variants', 'brand'] })
 * ```
 */
export default class Shopper {
  public readonly store: StoreModule

  public constructor(config: ShopperConfig) {
    this.store = new StoreModule(new HttpClient(config))
  }
}

export type { ShopperConfig } from './client'
export { StoreModule, type Paginated } from './store'
export { ShopperApiError, buildQuery } from './http'
export type { RequestParams, FetchOptions } from './http'
export { flatten } from './json-api'
export type {
  JsonApiDocument,
  JsonApiResource,
  JsonApiError,
  JsonApiErrorDocument,
  ResourceIdentifier,
} from './json-api'
