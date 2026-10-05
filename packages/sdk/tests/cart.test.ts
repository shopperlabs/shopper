import { describe, expect, it } from 'vitest'

import { HttpClient } from '../src/client'
import { CartModule } from '../src/store/cart'
import { fakeFetch, json } from './support'

describe('CartModule', () => {
  it('returns the transferred cart with the lines repriced by the transfer', async () => {
    const { fetch } = fakeFetch([
      json(200, {
        data: { type: 'carts', id: 'cart_1', attributes: { currency_code: 'USD' } },
        meta: { price_changes: [{ line: 'line_1', from: 1000, to: 800 }] },
      }),
    ])
    const cart = new CartModule(new HttpClient({ baseUrl: 'https://shop.test', fetch }))

    const transfer = await cart.transfer('cart_1')

    expect(transfer.cart.id).toBe('cart_1')
    expect(transfer.price_changes).toEqual([{ line: 'line_1', from: 1000, to: 800 }])
  })
})
