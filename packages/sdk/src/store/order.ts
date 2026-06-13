import type { Order } from '@shopperlabs/shopper-types'

import type { HttpClient } from '../client'
import type { RequestParams } from '../http'
import { flatten } from '../json-api'

/**
 * Order confirmation lookup. An order is addressed by the public id returned
 * by cart.complete(): a guest order is reachable by anyone holding that
 * unguessable id, a customer order only with the customer's token. Pass
 * `include: ['items']` to expand the lines.
 */
export class OrderModule {
  private readonly path: string

  public constructor(private readonly client: HttpClient) {
    this.path = `/${client.storePrefix}/orders`
  }

  public async retrieve(id: string, params?: RequestParams): Promise<Order> {
    return flatten<Order>(await this.client.request(`${this.path}/${id}`, params)) as Order
  }
}
