import type { Review } from '@shopperlabs/shopper-types'

import type { HttpClient } from '../client'
import type { FetchInit, RequestParams } from '../http'
import { flatten } from '../json-api'

/**
 * Shop-wide reviews. Product reviews live on the product resource
 * (`sdk.store.product.reviews(slug)`); this module carries what is not
 * scoped to a single product.
 */
export class ReviewModule {
  public constructor(private readonly client: HttpClient) {}

  /**
   * The most recent approved reviews across the shop, capped server-side and
   * never paginated: the shape a testimonials widget needs.
   */
  public async latest(params?: RequestParams, init?: FetchInit): Promise<Review[]> {
    const document = await this.client.request(`/${this.client.storePrefix}/reviews`, params, init)

    return (flatten<Review>(document) ?? []) as Review[]
  }
}
