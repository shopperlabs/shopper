# Currencies

Currencies play a crucial role in defining the prices of products, services, and other monetary details within your e-commerce system. 
Whether you're running a local store or a global marketplace.

Laravel Shopper is designed to support **multi-currency functionality**, allowing you to define prices in different currencies and tailor them to 
specific [zones](/docs/zones). This flexibility ensures that you can cater to a global audience without any limitations related to pricing. 
With Shopper, you can effortlessly set up and manage multiple currencies, ensuring that your customers see prices in their preferred currency, 
no matter where they are located.

In this documentation, you'll learn how to configure and utilize currencies within your Laravel Shopper project. Whether you're setting up 
your first currency or managing a complex multi-currency setup, this guide will provide you with the tools and knowledge you need to make the most 
of Shopper's currency features.

## Model

The model used is `Shopper\Core\Models\Currency`.

| Name            | Type    | Required | Notes                                                                                                                  |
|-----------------|---------|----------|------------------------------------------------------------------------------------------------------------------------|
| `id`            | autoinc |          | auto                                                                                                                   |
| `name`          | string  | yes      | Name of the currency                                                                                                   |
| `code`          | string  | yes      | `Unique`, indicating the [3 character ISO code](https://en.wikipedia.org/wiki/ISO_4217#Active_codes) for the currency. |
| `symbol`        | string  | yes      | string indicating the native symbol of the currency.                                                                   |
| `format`        | string  | yes      | string indicating the preview format of the currency.                                                                  |
| `exchange_rate` | float   | no       | The exchange rate relative to the default currency, default `0.0`                                                      |
| `is_enabled`    | boolean | no       | Indicate if the currency is enabled on the store. By defauft `true`                                                    |


## How Currencies are Created

Currencies are defined in the core of your Shopper backend into the file located at `core/database/data/currencies.php`. When you run the migration or 
seed command the first time for your Shopper store, a migration uses this data to insert all its properties (the currencies) into the database.

So, if you want to add other currencies, you can create a migration that inserts your currencies into the database, but Shopper basically installs 
a large number of currencies so you don't have to, over 150 currencies are created when Shopper is installed.

## How Setup Store Currencies

After installing Shopper, you need to [set up your store](/docs/setup-store) and during this step you'll choose the currencies you want for your 
store and set the one that will be used by default. But once you've done this, in your general settings `Settings > General`, you can change these currencies.

<div class="screenshot">
  <img src="/screenshots/{{version}}/store-currency.png" alt="Store currency">
  <div class="caption">Store currency</div>
</div>

## Relation to Other Entities

### Store

A store has a default currency and can have many currencies. These currencies are then used in other relations, such as when associating a zone with a currency.
These 2 values are available throw the `Shopper\Core\Models\Setting` Model, under the values `default_currency_id` and `currencies` for the `key` column.

The `shopper_setting('default_currency_id')` helper will return the id of the default currency and `shopper_setting('currencies')` will return and array of currencies setup for your store

### Zone

Each Zone is associated with a currency. A currency can be used in more than one zone, but a zone can have only one currency.

The relation is available on a Zone throw the `currency` relation and will return a `Shopper\Core\Models\Currency` object.
You can also access the currency code through the attribute `currency_code` on the zone.

### Price

The Price entity is used to represent a price associated with an entity, for example for products or variants. Each price is associated with a currency.

The relation is available on a Price throw the `currency` relation and will return a `Shopper\Core\Models\Currency` object.
You can also access the currency code through the attribute `currency_code` on the zone.
