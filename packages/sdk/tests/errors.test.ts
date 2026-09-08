import { describe, expect, it } from 'vitest'

import { ShopperApiError } from '../src/http'

describe('ShopperApiError', () => {
  it('exposes the first code and groups messages by field', () => {
    const error = new ShopperApiError(422, [
      { status: '422', code: 'unique', detail: 'The email has already been taken.', source: { pointer: '/data/attributes/email' } },
      { status: '422', code: 'required', detail: 'The last name field is required.', source: { pointer: '/data/attributes/last_name' } },
      { status: '422', code: 'currency_unknown', detail: 'Unknown currency.', source: { pointer: '/data/attributes/filter/currency' } },
      { status: '422', code: 'invalid', detail: 'No pointer here.' },
    ])

    expect(error.code).toBe('unique')
    expect(error.fields()).toEqual({
      email: ['The email has already been taken.'],
      last_name: ['The last name field is required.'],
      'filter.currency': ['Unknown currency.'],
    })
    expect(error.message).toBe('The email has already been taken.')
  })

  it('reports the code of a given field whatever the error order', () => {
    const error = new ShopperApiError(422, [
      { status: '422', code: 'min', detail: 'The password must be at least 8 characters.', source: { pointer: '/data/attributes/password' } },
      { status: '422', code: 'unique', detail: 'The email has already been taken.', source: { pointer: '/data/attributes/email' } },
      { status: '422', detail: 'No code here.', source: { pointer: '/data/attributes/last_name' } },
    ])

    expect(error.codeFor('email')).toBe('unique')
    expect(error.codeFor('password')).toBe('min')
    expect(error.codeFor('last_name')).toBeUndefined()
    expect(error.codeFor('first_name')).toBeUndefined()
  })
})
