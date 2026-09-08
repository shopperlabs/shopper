import { describe, expect, it } from 'vitest'

import { AuthModule } from '../src/auth'
import { HttpClient } from '../src/client'
import { fakeFetch, json } from './support'

const customer = { id: '01CUSTOMER', type: 'customers', attributes: { email: 'jane@example.com', last_name: 'Doe' } }

describe('AuthModule', () => {
  it('captures the token and the attached cart id from the authentication meta', async () => {
    const { fetch, calls } = fakeFetch([
      json(201, { data: customer, meta: { token: 'secret', cart_id: '01OWNEDCART' } }),
      json(204, {}),
    ])
    const client = new HttpClient({ baseUrl: 'https://shop.test', fetch })
    const auth = new AuthModule(client)

    await auth.register({ last_name: 'Doe', email: 'jane@example.com', password: 'super-secret', cart_id: '01GUESTCART' })

    expect(JSON.parse(String(calls[0]?.init.body))).toMatchObject({ cart_id: '01GUESTCART' })
    expect(auth.getToken()).toBe('secret')
    expect(auth.getCartId()).toBe('01OWNEDCART')

    await auth.logout()

    expect(auth.getToken()).toBeNull()
    expect(auth.getCartId()).toBeNull()
  })

  it('answers a null cart id when nothing was attached', async () => {
    const { fetch } = fakeFetch([json(200, { data: customer, meta: { token: 'secret', cart_id: null } })])
    const auth = new AuthModule(new HttpClient({ baseUrl: 'https://shop.test', fetch }))

    await auth.login({ email: 'jane@example.com', password: 'super-secret' })

    expect(auth.getCartId()).toBeNull()
  })
})
