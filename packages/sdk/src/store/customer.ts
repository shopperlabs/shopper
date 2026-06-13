import type { Address, Customer, Order } from '@shopperlabs/shopper-types'

import type { HttpClient } from '../client'
import type { RequestParams } from '../http'
import { flatten } from '../json-api'
import { CollectionResource } from './collection'

class CustomerAddressResource {
  public constructor(
    private readonly client: HttpClient,
    private readonly path: string,
  ) {}

  public async list(params?: RequestParams): Promise<Address[]> {
    return (flatten<Address>(await this.client.request(this.path, params)) ?? []) as Address[]
  }

  public async create(payload: Record<string, unknown>): Promise<Address> {
    const document = await this.client.send('POST', this.path, payload)

    return flatten<Address>(document as NonNullable<typeof document>) as Address
  }

  public async update(id: string, payload: Record<string, unknown>): Promise<Address> {
    const document = await this.client.send('PATCH', `${this.path}/${id}`, payload)

    return flatten<Address>(document as NonNullable<typeof document>) as Address
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
   * `include: ['items', 'shipping_address', 'billing_address', 'payment_method', 'shippings', 'shippings.events', 'refund']`.
   */
  public readonly orders: CollectionResource<Order>

  private readonly path: string

  public constructor(private readonly client: HttpClient) {
    this.path = `/${client.storePrefix}/customers/me`
    this.address = new CustomerAddressResource(client, `${this.path}/addresses`)
    this.orders = new CollectionResource<Order>(client, `${this.path}/orders`)
  }

  public async me(params?: RequestParams): Promise<Customer> {
    return flatten<Customer>(await this.client.request(this.path, params)) as Customer
  }

  public async update(payload: Record<string, unknown>): Promise<Customer> {
    const document = await this.client.send('PATCH', this.path, payload)

    return flatten<Customer>(document as NonNullable<typeof document>) as Customer
  }

  /** Upload a profile photo (image, max 1 MB). Returns the customer with the new avatar URL. */
  public async updateAvatar(file: Blob, filename?: string): Promise<Customer> {
    const form = new FormData()
    form.append('avatar', file, filename ?? 'avatar')

    const document = await this.client.send('POST', `${this.path}/avatar`, form)

    return flatten<Customer>(document as NonNullable<typeof document>) as Customer
  }

  /** Remove the uploaded photo and fall back to the generated avatar. */
  public async removeAvatar(): Promise<Customer> {
    const document = await this.client.send('DELETE', `${this.path}/avatar`)

    return flatten<Customer>(document as NonNullable<typeof document>) as Customer
  }
}
