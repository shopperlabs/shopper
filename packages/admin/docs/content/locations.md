# Locations

By default, when you install Laravel Shopper for the first time you configure and create your shop, and this allows you to create a first location which will have the information you defined. This is the initial location in which all products will be stored.

If you have already used [Shopify](https://shopify.com) it is a bit the same model, but a little simpler so that you can modify it because the system is accessible for any type of business.

You can set up multiple locations in your store so that you can track inventory and have excel documents of the products ordered in each of your locations. Your locations can be retail stores, warehouses, popups, or any other place where you manage or store inventory. With multiple locations, you have better visibility into your inventory in your business.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/location-admin.png" alt="Locations">
  <div class="caption">Locations</div>
</div>

A location is a physical place or space where you perform any or all of the following activities: Selling products, shipping or fulfillment orders, and inventory inventory (this may even be your apartment).

## Setup locations

From the administration area of your store you can't manage more than 4 inventories. This system being still experimental we are working on it to make it simpler to facilitate the management of your store.

### Fields

The model used is `Shopper\Core\Models\Inventory`. 

| Name                  | Type    | Required      | Notes                                                                                                     |
|-----------------------|---------|---------------|-----------------------------------------------------------------------------------------------------------|
| `id`                  | autoinc |               | auto                                                                                                      |
| `code`                | string  | yes           | Unique, the code is a unique element that allows to index an inventory in a unique way, a bit like a slug |
| `description`         | text    | no            | nullable                                                                                                  |
| `email`               | string  | yes           | Unique, the location email address                                                                        |
| `street_address`      | string  | yes           | The address details (street, nr, building, etc)                                                           |
| `street_address_plus` | string  | no            | The second address details (optional)                                                                     |
| `zipcode`             | string  | yes           | National identification code. (optional)                                                                  |
| `city`                | string  | yes           | The city/settlement                                                                                       |
| `phone_number`        | string  | no            | Nullable                                                                                                  |
| `priority`            | integer | default (`0`) | no                                                                                                        |
| `latitude`            | decimal | no            | Nullable, GPS latitude coordinates                                                                        |
| `longitude`           | decimal | no            | Nullable, GPS longitude coordinates                                                                       |
| `is_default`          | boolean | no            | Default `false`, define location as default for stock                                                     |
| `country_id`          | string  | yes           | foreign key for country, each location must be linked to a country                                        |

### Components

The components used to manage locations are found in the component configuration file `config/shopper/components/setting.php`.
This configuration file is not available by default. But if you want to update or change the settings components, you can publish them with the command

```bash
php artisan shopper:component:publish setting
```

```php
use Shopper\Livewire\Pages;

return [

    'pages' => [
        // ...
        'inventory-index' => Pages\Settings\Inventories\Browse::class,
        'inventory-create' => Pages\Settings\Inventories\Create::class,
        'inventory-edit' => Pages\Settings\Inventories\Edit::class,
        // ...
    ];
    
    'components' => [
        
    ],

];
```

You can expand each of its components to customize this section or replace the entire section if your system requires it.

You can also change the views too and return your own views.

## Manage locations

In your administration area you must click on the "cog" icon to display the settings page of your store.

- From your admin panel, on the blue sidebar click on the cog icon, go to `Settings > Locations`.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/settings-location.png" alt="Setting location">
  <div class="caption">Settings > Locations</div>
</div>

### Add location

In your administration area you must click on the "cog" icon to display the settings page of your store.

1. Click Add location.
2. Enter a unique name and an address for the location.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/location-create.png" alt="Add location">
  <div class="caption">Create location</div>
</div>

### Edit location

To update a location you click on an available location among those you have saved and you will have the update page.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/location-update.png" alt="update location">
  <div class="caption">Update location</div>
</div>

### Define a default location

The default location is the one in which all products will be collected with each order. If this location is empty the products will be searched in another and if it is the only one the product will be out of stock on your store.
You select a location and during the modification you click on the checkbox **Set as default inventory**

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/location-default.png" alt="Set default location">
  <div class="caption">Set default location</div>
</div>

### Delete location
To delete a location you must click on a location to display it and at the bottom of the page you click on the delete button.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/location-delete.png" alt="Delete location">
  <div class="caption">Delete location confirmation</div>
</div>

:::danger
Once the location removed, all inventories will be changed and this could complicate your inventory management. This is why you have a confirmation modal to be sure that this is indeed what you want to proceed.
:::
