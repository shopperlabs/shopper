# Orders

Orders are the heartbeat of your e-commerce store. They represent the culmination of your customers' journeys, from browsing products to completing purchases.
Effectively managing orders is essential to ensuring a seamless experience for both your customers and your team.
Order processing is one of the critical aspects of e-commerce business. Such activities as creating orders/create
invoices/create shipments, and refunds (if necessary), should always be organized logically.

This documentation serves as your comprehensive user guide to managing orders in Shopper.
Whether you're an administrator or a store manager, you'll find everything you need to navigate the order management process with ease.

The following pages will walk you through:

- Viewing and filtering orders.
- Create order.
- Updating order statuses.
- Managing customer information and order details.

## Overview

In Shopper, orders are typically created when a customer completes the checkout process on your store.
When a customer places an order from your store, on the admin panel, the order status is generated from where the admin can further process the order.

However, you also have the ability to update the Order Component to create and manage orders manually through the admin panel.
Orders are accessible to users with the appropriate permissions, ensuring that your store's operations remain secure and organized.

<div class="screenshot">
    <img src="/screenshots/{{version}}/orders.png" alt="Shopper Orders">
    <div class="caption">Orders</div>
</div>

## Models

Order management and processing in Shopper are powered by a set of Models that work together seamlessly. These Models represent the core entities 
involved in handling orders, from creation to fulfillment.

- [Order](#order) (`Shopper\Core\Models\Order`) represents a customer’s purchase, including details like order status, payment method, and total amount.
- [OrderItem](#orderitem) (`Shopper\Core\Models\OrderItem`) tracks individual products included in an order, along with quantities and prices.
- [OrderAddress](#orderaddress) (`Shopper\Core\Models\OrderAddress`) responsible for managing the shipping and billing addresses associated with an order
- [OrderRefund](#orderrefund) (`Shopper\Core\Models\OrderRefund`) manages refund requests and processes, including amounts and reasons for refunds.
- [OrderShipping](#ordershipping) (`Shopper\Core\Models\OrderShipping`)

### Order

As mentioned above, initially on Shopper it is not possible to create orders from the administration area. Orders can only be placed from your customer site, and will be available and processed in your administration area.
However, you have the option of publishing order-related [components](#components) to customize them to your needs.

#### Fields

| Name                  | Type      | Required | Notes                                                                        |
|-----------------------|-----------|----------|------------------------------------------------------------------------------|
| `id`                  | autoinc   |          | auto                                                                         |
| `number`              | string    | yes      | Order number, can be generate using the `generate_number()` helper           |
| `price_amount`        | int       | no       | The order price, if not set the price can be determine using the items price |
| `status`              | string    | yes      | `[OrderStatus](#enums-types)` Enum value                                     |
| `currency_code`       | string    | yes      |                                                                              |
| `notes`               | text      | no       |                                                                              |
| `parent_order_id`     | int       | no       | int (`Order` object via the `parent` relation)                               |
| `payment_method_id`   | int       | no       | int (`PaymentMethod` object via the `paymentMethod` relation)                |
| `channel_id`          | int       | no       | int (`Channel` object via the `channel` relation)                            |
| `customer_id`         | int       | no       | int (`User` object via the `customer` relation)                              |
| `zone_id`             | int       | no       | int (`Zone` object via the `zone` relation)                                  |
| `billing_address_id`  | int       | no       | int (`OrderAddress` object via the `billingAddress` relation)                |
| `shipping_address_id` | int       | no       | int (`OrderAddress` object via the `shippingAddress` relation)               |
| `shipping_option_id`  | int       | no       | int (`CarrierOption` object via the `shippingOption` relation)               |
| `canceled_at`         | timestamp | no       |                                                                              |

#### Create an Order

An order is created when a customer visits your online store, selects one or more products, and completes the checkout process.
When a customer places an order through the storefront, the order details are automatically generated in the admin panel. 
From there, administrators can view, update, and process the order as needed.

```php
use Shopper\Core\Models\Order;

$order = Order::query()->create([
    'number' => generate_number(),
    'customer_id' => Auth::id(),
    'currency_code' => current_currency(),
]);
```

But to get to this stage, you need to add a product to your basket, define a delivery and billing address, select your payment method and place your order.
Don't worry, Shopper provides [Starter kits](https://github.com/shopperlabs/starter-kits) like Breeze to give you a code base for setting up your storefront.

#### Front a storefront

**Step 1** > Open the Product Detail page and **Add the product** to the cart as shown below.

<div class="screenshot">
    <img src="/screenshots/{{version}}/store-product-detail.png" alt="product storefront">
    <div class="caption">Product details</div>
</div>

**Step 2** > Now proceed to checkout inside the Shopping Cart as shown below.

<div class="screenshot">
    <img src="/screenshots/{{version}}/cart.png" alt="Shopping cart storefront">
    <div class="caption">Shopping cart</div>
</div>

**Step 3** > Next, you will get redirected to the checkout page and fill in the necessary information regarding the Billing Address & Shipping Address as shown below.

<div class="screenshot">
    <img src="/screenshots/{{version}}/cart-step-address.png" alt="checkout address storefront">
    <div class="caption">Checkout billing address</div>
</div>

**Step 4** > After confirming add the shipping method and proceed to the payment step as shown in the below image.

<div class="screenshot">
    <img src="/screenshots/{{version}}/cart-step-shipping.png" alt="checkout shipping storefront">
    <div class="caption">Checkout shipping method</div>
</div>

**Step 5** > After choose your payment method, click on Place Order as shown in the below image.

<div class="screenshot">
    <img src="/screenshots/{{version}}/cart-step-payment.png" alt="checkout payment storefront">
    <div class="caption">Checkout payment</div>
</div>

**Step 6** > After clicking on Place Order, the next page will open like below then you will get an Order ID.

<div class="screenshot">
    <img src="/screenshots/{{version}}/order-place.png" alt="order place storefront">
    <div class="caption">Order place</div>
</div>

This order will be available in your administration cpanel.

<div class="screenshot">
    <img src="/screenshots/{{version}}/orders-with-zone.png" alt="Orders with zone">
    <div class="caption">Orders List with zones</div>
</div>

[Zones](/docs/{{version}}/zones) are markets in which customers can place orders. By default, zones are not configured, but if you plan to 
sell in different zones (Europe, Africa, or even a specific country such as England) you should configure them via your admin panel

### OrderItem

| Name                | Type    | Required | Notes                                                           |
|---------------------|---------|----------|-----------------------------------------------------------------|
| `id`                | autoinc |          | auto                                                            |
| `name`              | string  | no       | The product name at the moment of buying                        |
| `sku`               | string  | no       | Unique, default value is generated using collection name        |
| `product`           | morph   | yes      | morph relation generate `product_id` and `product_type` columns |
| `quantity`          | int     | yes      | Unique, default value is generated using collection name        |
| `unit_price_amount` | int     | yes      | Unique, default value is generated using collection name        |
| `order_id`          | int     | yes      | int (`Order` object via the `order` relation)                   |

### OrderAddress

| Name                  | Type    | Required | Notes                                           |
|-----------------------|---------|----------|-------------------------------------------------|
| `id`                  | autoinc |          | auto                                            |
| `customer_id`         | int     | yes      | int (`User` object via the `customer` relation) |
| `last_name`           | string  | yes      |                                                 |
| `first_name`          | string  | yes      |                                                 |
| `first_name`          | string  | yes      |                                                 |
| `company`             | string  | no       |                                                 |
| `street_address`      | string  | yes      |                                                 |
| `street_address_plus` | string  | no       |                                                 |
| `postal_code`         | string  | yes      |                                                 |
| `city`                | string  | yes      |                                                 |
| `phone`               | string  | yes      |                                                 |
| `country_name`        | string  | no       | The country name                                |

### OrderRefund

| Name       | Type    | Required | Notes                                            |
|------------|---------|----------|--------------------------------------------------|
| `id`       | autoinc |          | auto                                             |
| `reason`   | string  | no       | The reason of the refund                         |
| `amount`   | int     | yes      | The amount to refund                             |
| `currency` | string  | yes      |                                                  |
| `status`   | string  | yes      | `[OrderRefundStatus](#enums-types)` Enum value   |
| `notes`    | string  | no       | Other notes for the refund: by the administrator |
| `order_id` | int     | yes      | int (`Order` object via the `order` relation)    |
| `user_id`  | int     | no       | int (`User` object via the `customer` relation)  |

### OrderShipping

| Name              | Type     | Required | Notes                                                               |
|-------------------|----------|----------|---------------------------------------------------------------------|
| `id`              | autoinc  |          | auto                                                                |
| `shipped_at`      | datetime | yes      |                                                                     |
| `received_at`     | datetime | no       |                                                                     |
| `returned_at`     | datetime | no       |                                                                     |
| `tracking_number` | string   | no       |                                                                     |
| `tracking_url`    | string   | no       |                                                                     |
| `voucher`         | json     | no       |                                                                     |
| `order_id`        | int      | yes      | int (`Order` object via the `order` relation)                       |
| `carrier_id`      | int      | no       | int (`Carrier` object via the `carrier` relation) Eg: DHL, UPS, etc |

## Enums Types

...

## Components

...
