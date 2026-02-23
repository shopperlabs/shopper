import type { CarrierOption } from './carrier'
import type { Channel } from './channel'
import type { DateEntity, Entity, Metadata } from './common'
import type { Customer } from './customer'
import type { PaymentMethod } from './payment_method'
import type { Zone } from './zone'

export enum OrderStatus {
  NEW = 'new',
  PROCESSING = 'processing',
  COMPLETED = 'completed',
  CANCELLED = 'cancelled',
  ARCHIVED = 'archived',
}

export enum PaymentStatus {
  PENDING = 'pending',
  AUTHORIZED = 'authorized',
  PAID = 'paid',
  PARTIALLY_REFUNDED = 'partially_refunded',
  REFUNDED = 'refunded',
  VOIDED = 'voided',
}

export enum ShippingStatus {
  UNFULFILLED = 'unfulfilled',
  PARTIALLY_SHIPPED = 'partially_shipped',
  SHIPPED = 'shipped',
  PARTIALLY_DELIVERED = 'partially_delivered',
  DELIVERED = 'delivered',
  PARTIALLY_RETURNED = 'partially_returned',
  RETURNED = 'returned',
}

export enum ShipmentStatus {
  PENDING = 'pending',
  PICKED_UP = 'picked_up',
  IN_TRANSIT = 'in_transit',
  AT_SORTING_CENTER = 'at_sorting_center',
  OUT_FOR_DELIVERY = 'out_for_delivery',
  DELIVERED = 'delivered',
  DELIVERY_FAILED = 'delivery_failed',
  RETURNED = 'returned',
}

export enum FulfillmentStatus {
  PENDING = 'pending',
  FORWARDED_TO_SUPPLIER = 'forwarded_to_supplier',
  PROCESSING = 'processing',
  SHIPPED = 'shipped',
  DELIVERED = 'delivered',
  CANCELLED = 'cancelled',
}

export enum OrderRefundStatus {
  AWAITING = 'awaiting',
  PENDING = 'pending',
  TREATMENT = 'treatment',
  PARTIAL_REFUND = 'partial_refund',
  REFUNDED = 'refunded',
  REJECTED = 'rejected',
  CANCELLED = 'cancelled',
}

/**
 * Order model.
 */
export interface Order extends Entity {
  /** The order number. */
  number: string
  /** The total price amount (in cents). */
  price_amount: number
  /** The notes for the order. */
  notes: string | null
  /** The currency code for the order. */
  currency_code: string
  /** The computed total amount. */
  total_amount?: number
  /** The order status. */
  status: OrderStatus
  /** The payment status. */
  payment_status: PaymentStatus
  /** The shipping status. */
  shipping_status: ShippingStatus
  /** The date the order was cancelled. */
  cancelled_at: DateEntity | null
  /** The date the order was archived. */
  archived_at: DateEntity | null
  /** The zone ID. */
  zone_id: number | null
  /** The shipping address ID. */
  shipping_address_id: number | null
  /** The billing address ID. */
  billing_address_id: number | null
  /** The payment method ID. */
  payment_method_id: number | null
  /** The customer ID. */
  customer_id: number | null
  /** The channel ID. */
  channel_id: number | null
  /** The parent order ID (for split orders). */
  parent_order_id: number | null
  /** The metadata of the order. */
  metadata: Metadata
  /** The shipping option ID. */
  shipping_option_id?: number | null
  /** The shipping option. */
  shippingOption?: CarrierOption
  /** The shipping address. */
  shippingAddress?: OrderAddress | null
  /** The billing address. */
  billingAddress?: OrderAddress | null
  /** The payment method. */
  paymentMethod?: PaymentMethod | null
  /** The zone. */
  zone?: Zone | null
  /** The channel. */
  channel?: Channel | null
  /** The parent order. */
  parent?: Order | null
  /** The customer. */
  customer?: Customer
  /** The order items. */
  items?: OrderItem[]
  /** The order shippings. */
  shippings?: OrderShipping[]
  /** The child orders (for split orders). */
  children?: Order[]
  /** The refund for the order. */
  refund?: OrderRefund
}

/**
 * OrderItem model.
 */
export interface OrderItem extends Entity {
  /** The name of the order item. */
  name: string
  /** The quantity of the order item. */
  quantity: number
  /** The unit price amount. */
  unit_price_amount: number | null
  /** The computed total (unit_price_amount * quantity). */
  total: number
  /** The SKU of the order item. */
  sku: string | null
  /** The product ID. */
  product_id: number
  /** The product type (morph type). */
  product_type: string
  /** The order ID. */
  order_id: number
  /** The order shipping ID. */
  order_shipping_id: number | null
  /** The fulfillment status. */
  fulfillment_status: FulfillmentStatus | null
  /** The order. */
  order?: Order
  /** The shipment. */
  shipment?: OrderShipping
}

/**
 * OrderAddress model.
 */
export interface OrderAddress extends Entity {
  /** The first name. */
  first_name: string
  /** The last name. */
  last_name: string
  /** The computed full name. */
  full_name: string
  /** The street address. */
  street_address: string
  /** The additional street address. */
  street_address_plus: string | null
  /** The postal code. */
  postal_code: string
  /** The city. */
  city: string
  /** The state/province. */
  state: string | null
  /** The company name. */
  company: string | null
  /** The phone number. */
  phone: string | null
  /** The country name. */
  country_name: string | null
  /** The customer ID. */
  customer_id?: number | null
  /** The customer. */
  customer?: Customer
}

/**
 * OrderShipping model.
 */
export interface OrderShipping extends Entity {
  /** The shipment status. */
  status: ShipmentStatus | null
  /** The date the order was shipped. */
  shipped_at: DateEntity
  /** The date the order was received. */
  received_at: DateEntity | null
  /** The date the order was returned. */
  returned_at: DateEntity | null
  /** The tracking number. */
  tracking_number: string | null
  /** The tracking URL. */
  tracking_url: string | null
  /** The shipping voucher data. */
  voucher: Record<string, unknown> | null
  /** The order ID. */
  order_id: number
  /** The carrier ID. */
  carrier_id: number | null
  /** The order. */
  order?: Order
  /** The carrier. */
  carrier?: import('./carrier').Carrier
  /** The items in this shipment. */
  items?: OrderItem[]
  /** The shipment tracking events. */
  events?: OrderShippingEvent[]
}

/**
 * OrderShippingEvent model.
 */
export interface OrderShippingEvent {
  id: number
  /** The event status. */
  status: ShipmentStatus
  /** The description of the event. */
  description: string | null
  /** The location of the event. */
  location: string | null
  /** The latitude of the event. */
  latitude: number | null
  /** The longitude of the event. */
  longitude: number | null
  /** The metadata of the event. */
  metadata: Metadata
  /** The date the event occurred. */
  occurred_at: DateEntity
  /** The creation date. */
  created_at?: DateEntity
  /** The order shipping ID. */
  order_shipping_id: number
  /** The shipment this event belongs to. */
  shipment?: OrderShipping
}

/**
 * OrderRefund model.
 */
export interface OrderRefund extends Entity {
  /** The reason for the refund. */
  refund_reason: string | null
  /** The refund amount (in cents). */
  refund_amount: number | null
  /** The refund status. */
  status: OrderRefundStatus
  /** Additional notes. */
  notes: string | null
  /** The currency code. */
  currency: string
  /** The order ID. */
  order_id: number
  /** The user ID who processed the refund. */
  user_id: number | null
  /** The metadata of the refund. */
  metadata: Metadata
  /** The order. */
  order?: Order
}
