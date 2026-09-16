# Shopper FedEx

The FedEx shipping driver for [Shopper](https://laravelshopper.dev). It quotes real time rates
at checkout and pulls the tracking timeline of every shipment into the order.

## Requirements

- PHP 8.3+
- Laravel 12.x or 13.x
- [`shopper/framework`](https://github.com/shopperlabs/shopper) installed and migrated

## Installation

```bash
composer require shopper/fedex
```

The service provider is auto-discovered and registers the `fedex` driver on the shipping
manager. Enable it and add the credentials issued by the FedEx developer portal to `.env`:

```dotenv
SHIPPING_FEDEX_ENABLED=true
SHIPPING_SANDBOX=true
FEDEX_CLIENT_ID=...
FEDEX_CLIENT_SECRET=...
FEDEX_ACCOUNT_NUMBER=...
```

A driver left disabled stays out of the carriers the admin offers, even once its credentials
are filled in. Rates are quoted against the account number.

## Tracking

Shipments handed to FedEx are refreshed every thirty minutes by the `shopper:shipments:sync-tracking`
scheduled command from `shopper/shipping`. The driver does not generate labels: buy the label on
the FedEx portal and paste the tracking number on the shipment.

## Configuration

```bash
php artisan vendor:publish --tag=shopper-fedex-config
```

This publishes `config/shopper/fedex.php`.

## Documentation

Read the [shipping documentation](https://docs.laravelshopper.dev) for carriers, fulfillment
and the tracking scheduler.
