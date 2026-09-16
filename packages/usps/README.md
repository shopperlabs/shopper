# Shopper USPS

The USPS shipping driver for [Shopper](https://laravelshopper.dev). It quotes real time rates
at checkout.

## Requirements

- PHP 8.3+
- Laravel 12.x or 13.x
- [`shopper/framework`](https://github.com/shopperlabs/shopper) installed and migrated

## Installation

```bash
composer require shopper/usps
```

The service provider is auto-discovered and registers the `usps` driver on the shipping
manager. Enable it and add the credentials issued by the USPS developer portal to `.env`:

```dotenv
SHIPPING_USPS_ENABLED=true
SHIPPING_SANDBOX=true
USPS_CLIENT_ID=...
USPS_CLIENT_SECRET=...
```

A driver left disabled stays out of the carriers the admin offers, even once its credentials
are filled in.

The driver does not track shipments nor generate labels: buy the label on the USPS portal and
paste the tracking number on the shipment.

## Configuration

```bash
php artisan vendor:publish --tag=shopper-usps-config
```

This publishes `config/shopper/usps.php`.

## Documentation

Read the [shipping documentation](https://docs.laravelshopper.dev) for carriers and fulfillment.
