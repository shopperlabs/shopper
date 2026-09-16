# Shopper UPS

The UPS shipping driver for [Shopper](https://laravelshopper.dev). It quotes real time rates
at checkout and pulls the tracking timeline of every shipment into the order.

## Requirements

- PHP 8.3+
- Laravel 12.x or 13.x
- [`shopper/framework`](https://github.com/shopperlabs/shopper) installed and migrated

## Installation

```bash
composer require shopper/ups
```

The service provider is auto-discovered and registers the `ups` driver on the shipping
manager. Enable it and add the credentials issued by the UPS developer portal to `.env`:

```dotenv
SHIPPING_UPS_ENABLED=true
SHIPPING_SANDBOX=true
UPS_CLIENT_ID=...
UPS_CLIENT_SECRET=...
UPS_USER_ID=...
UPS_ACCOUNT_NUMBER=...
```

A driver left disabled stays out of the carriers the admin offers, even once its credentials
are filled in. The account number is the shipper number your negotiated rates are attached to.

## Tracking

Shipments handed to UPS are refreshed every thirty minutes by the `shopper:shipments:sync-tracking`
scheduled command from `shopper/shipping`. The driver does not generate labels: buy the label on
the UPS portal and paste the tracking number on the shipment.

## Configuration

```bash
php artisan vendor:publish --tag=shopper-ups-config
```

This publishes `config/shopper/ups.php`.

## Documentation

Read the [shipping documentation](https://docs.laravelshopper.dev) for carriers, fulfillment
and the tracking scheduler.
