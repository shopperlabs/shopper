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

  private readonly client: HttpClient

  public constructor(config: ShopperConfig) {
    this.client = new HttpClient(config)

    this.store = new StoreModule(this.client)
    this.auth = new AuthModule(this.client)
  }

  /** Switch the language of subsequent calls; null reverts to the shop default. */
  public setLocale(locale: string | null): void {
    this.client.setLocale(locale)
  }

  public getLocale(): string | null {
    return this.client.getLocale()
  }

  /** Switch the sales channel of subsequent calls; null reverts to the unfiltered catalog. */
  public setChannel(channel: string | null): void {
    this.client.setChannel(channel)
  }

  public getChannel(): string | null {
    return this.client.getChannel()
  }
}

export type { ShopperConfig, TokenStorage } from './client'
export { AuthModule } from './auth'
export type { RegisterPayload, LoginPayload, ResetPasswordPayload } from './auth'
export { StoreModule, CustomerModule, CartModule, type Paginated } from './store'
export type {
  CreateCartPayload,
  CreateCartLinePayload,
  UpdateCartLinePayload,
  UpdateCartPayload,
  ShippingOptionList,
} from './store'
export { ShopperApiError, buildQuery } from './http'
export type { RequestParams, FetchOptions, FetchRequestOptions, FetchInit } from './http'
export type * from '@shopperlabs/shopper-types'
export { flatten } from './json-api'
export type {
  JsonApiDocument,
  JsonApiResource,
  JsonApiError,
  JsonApiErrorDocument,
  ResourceIdentifier,
} from './json-api'
