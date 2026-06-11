import { AuthModule } from './auth'
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
 *
 * await sdk.auth.login({ email, password })
 * const me = await sdk.store.customer.me()
 * ```
 */
export default class Shopper {
  public readonly store: StoreModule

  public readonly auth: AuthModule

  public constructor(config: ShopperConfig) {
    const client = new HttpClient(config)

    this.store = new StoreModule(client)
    this.auth = new AuthModule(client)
  }
}

export type { ShopperConfig, TokenStorage } from './client'
export { AuthModule } from './auth'
export type { RegisterPayload, LoginPayload, ResetPasswordPayload } from './auth'
export { StoreModule, CustomerModule, type Paginated } from './store'
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
