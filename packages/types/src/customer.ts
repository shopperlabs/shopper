import type { Address } from './address'
import type { Entity } from './common'

/**
 * The Gender Type for the customer.
 */
export enum GenderType {
  MALE = 'male',
  FEMALE = 'female',
}

/**
 * Customer model.
 */
export interface Customer extends Entity {
  /** The first name of the customer. */
  first_name: string | null
  /** The last name of the customer. */
  last_name: string
  /** The email of the customer. */
  email: string
  /** The gender of the customer. */
  gender: GenderType | null
  /** The phone number of the customer. */
  phone_number: string | null
  /** The birthdate of the customer. */
  birth_date: Date | null
  /** The date the email was verified. */
  email_verified_at: Date | null
  /** Whether the email is verified (store API). */
  email_verified?: boolean
  /** The avatar URL (uploaded file, or a generated fallback). */
  avatar: string
  /** The avatar type: storage|avatar_ui. */
  avatar_type: string
  /** The timezone of the customer. */
  timezone?: string | null
  /** Whether the customer has opted in to marketing. */
  opt_in: boolean
  /** The last login date. */
  last_login_at: string | null
  /** The last login IP address. */
  last_login_ip?: string | null
  /** The customer's addresses. */
  addresses?: Address[]
}
