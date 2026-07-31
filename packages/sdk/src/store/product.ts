import type { Product, Review, ReviewAggregates } from '@shopperlabs/shopper-types'

import type { FetchInit, RequestParams } from '../http'
import { flatten } from '../json-api'
import { CollectionResource, type Paginated } from './collection'

/**
 * A paginated products listing: the rows plus the currency every price-aware
 * value of the response is expressed in (`price_range`, price sorting).
 */
export interface ProductList extends Paginated<Product> {
  meta: { currency: string | null } & Record<string, unknown>
}

/**
 * A paginated reviews listing: the rows plus the server-computed aggregates
 * (count, average, star distribution) merged into the JSON:API meta.
 */
export interface ReviewList extends Paginated<Review> {
  meta: ReviewAggregates & Record<string, unknown>
}

/**
 * The products resource: the shared list/retrieve plus the product-scoped
 * sub-resources. Reviews belong to products in Shopper, so they are read
 * from here; an addon exposing reviews on a custom model brings its own
 * route, consumed through `store.fetch()`.
 */
export class ProductResource extends CollectionResource<Product> {
  public override async list(params?: RequestParams, init?: FetchInit): Promise<ProductList> {
    return (await super.list(params, init)) as ProductList
  }

  /**
   * The approved reviews of a product:
   *
   * ```ts
   * const { data, meta } = await sdk.store.product.reviews(slug, {
   *   filter: { rating: 5 },
   *   page: { size: 10 },
   * })
   * // meta.reviews_count, meta.average_rating, meta.rating_distribution
   * ```
   */
  public async reviews(slug: string, params?: RequestParams, init?: FetchInit): Promise<ReviewList> {
    const document = await this.client.request(`${this.path}/${slug}/reviews`, params, init)

    return {
      data: (flatten<Review>(document) ?? []) as Review[],
      meta: (document.meta ?? {}) as ReviewList['meta'],
      links: document.links,
    }
  }
}
