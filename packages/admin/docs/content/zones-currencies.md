# Zones and Currencies

## Zones

Coming Soon...

## Currencies

Choose the default currency for the store. Only one may be selected.

<div class="screenshot">
  <img src="/screenshots/{{version}}/store-currency.png" alt="Store currency">
  <div class="caption">Store currency</div>
</div>

For currency configurations we use the [akaunting/laravel-money](https://github.com/akaunting/laravel-money) package.
At the moment, the formatter does it automatically depending on the currency. There is also a helper that returns the currency you registered `shopper_currency()`.
This will return the currency configured in your admin panel: **USD**, **XAF**, **EUR**, etc

### Zero-decimal Currencies
