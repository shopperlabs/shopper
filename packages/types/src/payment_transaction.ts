import type { Entity, Metadata } from './common'
import type { Order } from './order'
import type { PaymentMethod } from './payment_method'

export enum TransactionStatus {
  PENDING = 'pending',
  SUCCESS = 'success',
  FAILED = 'failed',
}

export enum TransactionType {
  INITIATE = 'initiate',
  AUTHORIZE = 'authorize',
  CAPTURE = 'capture',
  REFUND = 'refund',
  CANCEL = 'cancel',
}

/**
 * PaymentTransaction model.
 */
export interface PaymentTransaction extends Entity {
  /** The payment driver used. */
  driver: string
  /** The transaction type. */
  type: TransactionType
  /** The transaction status. */
  status: TransactionStatus
  /** The transaction amount (in cents). */
  amount: number
  /** The currency code. */
  currency_code: string
  /** The external reference ID. */
  reference: string | null
  /** The transaction data from the provider. */
  data: Record<string, unknown> | null
  /** Additional notes. */
  notes: string | null
  /** The metadata of the transaction. */
  metadata: Metadata
  /** The order ID. */
  order_id: number | null
  /** The payment method ID. */
  payment_method_id: number | null
  /** The order. */
  order?: Order
  /** The payment method. */
  paymentMethod?: PaymentMethod
}
