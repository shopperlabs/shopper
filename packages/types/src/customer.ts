import type { Address } from './address'
import type { DateEntity, Entity } from './common'

/**
 * The Gender Type for the customer.
 */
export enum GenderType {
  MALE = 'male',
  FEMALE = 'female',
}

/**
 * The Avatar interface for the customer.
 */
export interface AvatarType {
  type: string
  url: string
  default: string
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
  /** The avatar of the customer. */
  avatar: AvatarType
  /** The timezone of the customer. */
  timezone?: string | null
  /** Whether the customer has opted in to marketing. */
  opt_in: boolean
  /** The last login date. */
  last_login_at: DateEntity | null
  /** The last login IP address. */
  last_login_ip?: string | null
  /** The customer's addresses. */
  addresses?: Address[]
}
