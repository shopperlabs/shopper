import type { OrderShipping } from '@shopperlabs/shopper-types'

import type { HttpClient } from '../client'
import type { RequestParams } from '../http'
import { flatten, type Flattened } from '../json-api'

export class ShipmentModule {
  private readonly path: string

  public constructor(private readonly client: HttpClient) {
    this.path = `/${client.storePrefix}/shipments`
  }

  public async retrieve(id: string, params?: RequestParams): Promise<Flattened<OrderShipping>> {
    return flatten<OrderShipping>(await this.client.request(`${this.path}/${id}`, params)) as Flattened<OrderShipping>
  }
}
