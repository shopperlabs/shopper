# Locations

By default, when you install Shopper for the first time, you configure and create your shop and this allows you to create a first location which will have the information you defined. 
This is the initial location in which all products are stored.

If you have already used [Shopify](https://shopify.com) it's a bit the same model, but a little simpler so that you can update it because the system is accessible for any type of business.

You can set up multiple locations in your store so that you can track inventory and have Excel documents of the products ordered in each of your locations. 
Your locations can be *retail stores*, *warehouses*, *popups*, or any other place where you manage or store inventory. With multiple locations, you have better visibility into your inventory in your business.

A location is a physical place or space where you perform any or all of the following activities: Selling products, shipping or fulfillment orders, and inventory (this may even be your apartment).

## Setup locations

From the administration area of your store you can't manage more than 4 inventories. This system being still experimental we are working on it to make it simpler to facilitate the management of your store.
You can change this value if you want to be able to manage many more inventories. The configuration to update is in the file `config/shopper/admin.php` on the key `inventory_limit`.

```php filename=config/shopper/admin.php
'inventory_limit' => 4,
```

### Fields

The model used is `Shopper\Core\Models\Inventory`. 

| Name                  | Type    | Required      | Notes                                                                                                     |
|-----------------------|---------|---------------|-----------------------------------------------------------------------------------------------------------|
| `id`                  | autoinc |               | auto                                                                                                      |
| `name`                | string  | yes           | Name of the inventory                                                                                     |
| `code`                | string  | yes           | Unique, the code is a unique element that allows to index an inventory in a unique way, a bit like a slug |
| `description`         | text    | no            | nullable                                                                                                  |
| `email`               | string  | yes           | Unique, the location email address                                                                        |
| `street_address`      | string  | yes           | The address details (street, nr, building, etc)                                                           |
| `street_address_plus` | string  | no            | The second address details (optional)                                                                     |
| `postal_code`         | string  | yes           | National identification code. (optional)                                                                  |
| `city`                | string  | yes           | The city/settlement                                                                                       |
| `phone_number`        | string  | no            | Nullable                                                                                                  |
| `priority`            | integer | default (`0`) | N/A                                                                                                       |
| `latitude`            | decimal | no            | Nullable, GPS latitude coordinates                                                                        |
| `longitude`           | decimal | no            | Nullable, GPS longitude coordinates                                                                       |
| `is_default`          | boolean | no            | Default `false`, define location as default for stock                                                     |
| `country_id`          | string  | yes           | foreign key for country, each location must be linked to a Country                                        |

### Components

The components used to manage locations are found in the component configuration file `config/shopper/components/setting.php`.
This configuration file is not available by default. But if you want to update or change the settings components, you can publish them with the command

```bash
php artisan shopper:component:publish setting
```

This file contains all Livewire components for settings. Here is only the list of inventory components

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
        // ...
    ],

];
```

You can expand each of its components to customize this section or replace the entire section if your system requires it.

You can also change the views too and return your own views.

## Manage locations

In your administration area you must click on the "cog" icon to display the settings page of your store.

From your admin panel, on the blue sidebar click on the cog icon, go to `Settings > Locations`.

<div class="screenshot">
  <img src="/screenshots/{{version}}/setting-locations.png" alt="Setting location">
  <div class="caption">Settings > Locations</div>
</div>

### Add location

To create a new location, in your Locations page:

- Click on "New inventory".
- Enter a unique name and an address for the location.
- Fill all the required fields
- Click the "Save" button

<div class="screenshot">
  <img src="/screenshots/{{version}}/setting-location-create.png" alt="Add location">
  <div class="caption">Create location</div>
</div>

### Edit location

To update a location you click on an available location.

- Click on the "edit icon" of the location you want to update
- Edit any of the location’s details.
- Click the "Save" button

### Define a default location

The default location is the one in which all products will be collected with each order. If this location is empty the 
products will be searched in another and if it is the only one the product will be out of stock on your store.

To set a default location:
- Go to the locations page
- Click on the edit icon for the location you want to set as default
- Toggle the **Set as default inventory** and hit the save button

<div class="screenshot">
  <img src="/screenshots/{{version}}/setting-location-default.png" alt="Default location">
  <div class="caption">Choose default location</div>
</div>

### Delete location

:::danger
Once the location removed, all inventories will be changed and this could complicate your inventory management.
This is why you have a confirmation modal to be sure that this is indeed what you want to proceed.
:::

To delete a location:
- Go to the inventory page
- Find the location you want to delete and click on the <x-untitledui-trash-03 class="size-5 text-red-500" stroke-width="1.5" aria-hidden="true"> icon at its right.
- Confirm your action on the modal to delete the location
