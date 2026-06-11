import type { Customer } from '@shopperlabs/shopper-types'

import type { HttpClient } from './client'
import { flatten } from './json-api'

export interface RegisterPayload {
  first_name?: string
  last_name: string
  email: string
  password: string
  opt_in?: boolean
}

export interface LoginPayload {
  email: string
  password: string
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

  private async authenticate(endpoint: string, payload: Record<string, unknown>): Promise<Customer> {
    const document = await this.client.send('POST', `/${this.client.storePrefix}/auth/${endpoint}`, payload)

    if (document === null) {
      throw new Error(`Unexpected empty response from /auth/${endpoint}.`)
    }

    const token = document.meta?.token

    if (typeof token === 'string') {
      this.client.tokens.set(token)
    }

    return flatten<Customer>(document) as Customer
  }
}
