import type { Address, Customer, Order, ResourceId } from '@shopperlabs/shopper-types'

import type { HttpClient } from '../client'
import type { RequestParams } from '../http'
import { flatten, type Flattened } from '../json-api'
import { CollectionResource } from './collection'

class CustomerAddressResource {
  public constructor(
    private readonly client: HttpClient,
    private readonly path: string,
  ) {}

  public async list(params?: RequestParams): Promise<Flattened<Address>[]> {
    return (flatten<Address>(await this.client.request(this.path, params)) ?? []) as Flattened<Address>[]
  }

  public async create(payload: Record<string, unknown>): Promise<Flattened<Address>> {
    const document = await this.client.send('POST', this.path, payload)

    return flatten<Address>(document as NonNullable<typeof document>) as Flattened<Address>
  }

  public async update(id: string, payload: Record<string, unknown>): Promise<Flattened<Address>> {
    const document = await this.client.send('PATCH', `${this.path}/${id}`, payload)

    return flatten<Address>(document as NonNullable<typeof document>) as Flattened<Address>
  }

  public async delete(id: string): Promise<void> {
    await this.client.send('DELETE', `${this.path}/${id}`)
  }
}

/**
 * Authenticated customer account: profile, addresses and orders.
 * Every call requires a token set by sdk.auth.login() or sdk.auth.register().
 */
export class CustomerModule {
  public readonly address: CustomerAddressResource

  /**
   * The customer's orders. list() answers a summary per order (number,
   * statuses, totals); retrieve() is the full account view, expandable with
   * `include: ['items', 'shippingAddress', 'billingAddress', 'paymentMethod', 'shippings', 'shippings.events', 'refund']`.
   */
  public readonly orders: CollectionResource<Order>

  private readonly path: string

  public constructor(private readonly client: HttpClient) {
    this.path = `/${client.storePrefix}/customers/me`
    this.address = new CustomerAddressResource(client, `${this.path}/addresses`)
    this.orders = new CollectionResource<Order>(client, `${this.path}/orders`)
  }

  public async me(params?: RequestParams): Promise<Flattened<Customer>> {
    return flatten<Customer>(await this.client.request(this.path, params)) as Flattened<Customer>
  }

  public async update(payload: Record<string, unknown>): Promise<Flattened<Customer>> {
    const document = await this.client.send('PATCH', this.path, payload)

    return flatten<Customer>(document as NonNullable<typeof document>) as Flattened<Customer>
  }

  /** Upload a profile photo (image, max 1 MB). Returns the customer with the new avatar URL. */
  public async updateAvatar(file: Blob, filename?: string): Promise<Flattened<Customer>> {
    const form = new FormData()
    form.append('avatar', file, filename ?? 'avatar')

    const document = await this.client.send('POST', `${this.path}/avatar`, form)

    return flatten<Customer>(document as NonNullable<typeof document>) as Flattened<Customer>
  }

  /**
   * A temporary link to download a file of a paid order. It expires after
   * five minutes: request one per download and send the browser to its url.
   */
  public async fileLink(orderId: ResourceId, itemId: ResourceId, fileId: ResourceId): Promise<{ url: string, expires_at: string }> {
    const document = await this.client.request(`${this.path}/orders/${orderId}/items/${itemId}/files/${fileId}`)

    return document.data as unknown as { url: string, expires_at: string }
  }

  /** Remove the uploaded photo and fall back to the generated avatar. */
  public async removeAvatar(): Promise<Flattened<Customer>> {
    const document = await this.client.send('DELETE', `${this.path}/avatar`)

    return flatten<Customer>(document as NonNullable<typeof document>) as Flattened<Customer>
  }
}
