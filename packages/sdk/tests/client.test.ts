import { describe, expect, it, vi } from 'vitest'

import { HttpClient } from '../src/client'
import { ShopperApiError } from '../src/http'
import { fakeFetch, json } from './support'

const unauthorized = json(401, { errors: [{ status: '401', code: 'unauthenticated', title: 'Unauthorized', detail: 'Unauthenticated.' }] })
const forbidden = json(403, { errors: [{ status: '403', code: 'forbidden', title: 'Forbidden', detail: 'Forbidden' }] })

describe('HttpClient', () => {
  it('clears the token and calls onUnauthorized once on a 401 carrying a token', async () => {
    const onUnauthorized = vi.fn()
    const { fetch } = fakeFetch([unauthorized])
    const client = new HttpClient({ baseUrl: 'https://shop.test', fetch, onUnauthorized })

    client.tokens.set('secret')

    await expect(client.request('/store/customers/me')).rejects.toBeInstanceOf(ShopperApiError)

    expect(onUnauthorized).toHaveBeenCalledTimes(1)
    expect(client.tokens.get()).toBeNull()
  })

  it('fires onUnauthorized when the Authorization header comes from the configured headers', async () => {
    const onUnauthorized = vi.fn()
    const { fetch } = fakeFetch([unauthorized])
    const client = new HttpClient({
      baseUrl: 'https://shop.test',
      fetch,
      onUnauthorized,
      headers: { Authorization: 'Bearer static-token' },
    })

    await expect(client.request('/store/customers/me')).rejects.toBeInstanceOf(ShopperApiError)

    expect(onUnauthorized).toHaveBeenCalledTimes(1)
  })

  it('leaves an anonymous 401 and a 403 alone', async () => {
    const onUnauthorized = vi.fn()
    const { fetch } = fakeFetch([unauthorized, forbidden])
    const client = new HttpClient({ baseUrl: 'https://shop.test', fetch, onUnauthorized })

    await expect(client.request('/store/customers/me')).rejects.toMatchObject({ status: 401 })

    client.tokens.set('secret')

    await expect(client.request('/store/customers/me')).rejects.toMatchObject({ status: 403 })

    expect(onUnauthorized).not.toHaveBeenCalled()
    expect(client.tokens.get()).toBe('secret')
  })
})
