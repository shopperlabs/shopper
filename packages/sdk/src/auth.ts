import type { Customer } from '@shopperlabs/shopper-types'

import type { HttpClient } from './client'
import { flatten } from './json-api'

export interface RegisterPayload {
  first_name?: string
  last_name: string
  email: string
  password: string
  opt_in?: boolean
  /** A guest cart to attach to the new account; the resulting cart id is exposed by getCartId(). */
  cart_id?: string
}

export interface LoginPayload {
  email: string
  password: string
  /** A guest cart to attach to the customer, folded into the cart they already own when one exists. */
  cart_id?: string
}

export interface ResetPasswordPayload {
  email: string
  token: string
  password: string
}

/**
 * Customer authentication: a successful register or login stores the Sanctum token in the client
 * (memory by default, custom storage via the tokenStorage config) and every subsequent request sends it
 * as a Bearer header. logout() revokes the token server side and purges it.
 */
export class AuthModule {
  private cartId: string | null = null

  public constructor(private readonly client: HttpClient) {}

  public async register(payload: RegisterPayload): Promise<Customer> {
    return this.authenticate('register', { ...payload })
  }

  public async login(payload: LoginPayload): Promise<Customer> {
    return this.authenticate('login', { ...payload })
  }

  public async logout(): Promise<void> {
    try {
      await this.client.send('POST', `/${this.client.storePrefix}/auth/logout`)
    } finally {
      this.client.tokens.clear()
      this.cartId = null
    }
  }

  public async forgotPassword(email: string): Promise<void> {
    await this.client.send('POST', `/${this.client.storePrefix}/auth/forgot-password`, { email })
  }

  public async resetPassword(payload: ResetPasswordPayload): Promise<void> {
    await this.client.send('POST', `/${this.client.storePrefix}/auth/reset-password`, { ...payload })
  }

  /** The token currently attached to requests, if any. */
  public getToken(): string | null {
    return this.client.tokens.get()
  }

  /**
   * The id of the cart attached during the last register() or login() call,
   * when a cart_id was sent. It may differ from the id sent: a guest cart
   * folded into the cart the customer already owned is gone after the merge.
   */
  public getCartId(): string | null {
    return this.cartId
  }

  private async authenticate(endpoint: string, payload: Record<string, unknown>): Promise<Customer> {
    const document = await this.client.send('POST', `/${this.client.storePrefix}/auth/${endpoint}`, payload)

    if (document === null) {
      throw new Error(`Unexpected empty response from /auth/${endpoint}.`)
    }

    const token = document.meta?.token

    if (typeof token === 'string') {
      this.client.tokens.set(token)
    }

    const cartId = document.meta?.cart_id

    this.cartId = typeof cartId === 'string' ? cartId : null

    return flatten<Customer>(document) as Customer
  }
}
