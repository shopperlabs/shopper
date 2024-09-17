# Initial Set-Up

Once you have installed Shopper, you need to set up a store to serve as your first location. 
After creating a new user, you need to login via the url `/cpanel/login`. After logging in you need to fill 
in the required information to access the Shopper dashboard

Defines your shop’s address country and state, where you are based as a seller. It'll determines default tax rates and customer locations.

## Global

All this information is stored using the **Setting** Model which is located `\Shopper\Core\Models\Setting`

```php
namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'display_name',
        'key',
        'value',
        'locked',
    ];
}
```

### Fields

| Name           | Type    | Notes                                                                                            |
|----------------|---------|--------------------------------------------------------------------------------------------------|
| `id`           | autoinc |                                                                                                  |
| `key`          | string  | Unique, the configuration key that will be used to retrieve this information.                    |
| `display_name` | string  | Nullable, represents the display name for the key that has been set for better reading           |
| `value`        | json    | Nullable, represents the value of the key that will be displayed when the parameter is requested |
| `locked`       | boolean | default is `false` (0), allows to define if this parameter can be updated                        |

### Retrieving setting

By default, to retrieve the value of a key you can use the helper function `shopper_setting()` passing the desired key as parameter

```php
shopper_setting('my_key')
```

This value is cached for one day and under the key `shopper-setting-{$key}` by default, but if you want to retrieve each time the latest value you pass the second param `withCache` to **false**

```php
shopper_setting('my_key', withCache: false);
```

## Store

When you launch your store the first important thing to do is to fill in the information about this store.
Your customers and the different services you might use need to know the information about your store.

<div class="screenshot" aria-hidden="true">
  <img src="/screenshots/{{version}}/initialization-step-1.png" alt="Store information">
  <div class="caption">Store informations</div>
</div>

The information stored in this section is available using the following keys:
- `name` the store name,
- `email` the store email
- `currencies` array ids for the store currencies
- `default_currency_id` default currency id of the store
- `country_id` default country location of the store.
- `about` description and information about your store

## Address

Most stores keep their products in different locations around the world. When setting up this configuration you need to 
define an address that will be set as the default location for your products.

When shipping an order, the products to be delivered/shipped will start from this location and thus the shipping price can be set according to this.

<div class="screenshot" aria-hidden="true">
  <img src="/img/screenshots/{{version}}/initialization-step-2.png" alt="Store address">
  <div class="caption">Store address</div>
</div>

The keys registered in database during this section: `street_address`, `postal_code`, `city`, `phone_number`

## Social Links

If you want your customers to find you easily on social networks, you can fill in all the links directly by putting the full url.

This step is completely optional

<div class="screenshot" aria-hidden="true">
  <img src="/screenshots/{{version}}/initialization-step-3.png" alt="Store social links">
  <div class="caption">Store social links</div>
</div>

The keys registered in the database during this section: `facebook_link`, `instagram_link` and `twitter_link`.

:::info
All Livewire components used during these steps are not customizable, as they are part of the Shopper core. 
And this is a mandatory step in the configuration of your store.
:::

## Channel

In today's E-commerce the shop site is no longer the only point of sale. Channels represent a single sales channel,
which can be one of the following things:

- Website
- Mobile application
- Cashier in your physical store
- Facebook shop,
- Instagram shop,
- etc

or pretty much anything similar you can imagine. When you set up your store Shopper creates a sales channel at 
the same time as your first location with the same address information.

```php
(new ChannelRepository())->create([
  'name' => $name = __('Web Store'),
  'slug' => $name,
  'url' => env('APP_URL'),
  'is_default' => true,
]);
```

This sales channel will be automatically assigned to all products that are added to your site. The implementation of a sales channel management will be done later

## Update setting

You can update your store information when needed, edit your store images, update the complete address, the legal name of your shop, etc.
To edit your shop information:

- From your administration, on the sidebar click on the settings icon at the bottom of the page **Settings > General**

The component used to update store setting of the store is found in the component configuration file `config/shopper/components/setting.php` if published, 
It's the `Shopper\Livewire\Pages\Settings\General` component under the key `pages.general`.

```php
use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;

return [
    'pages' => [
        'setting-index' => Pages\Settings\Index::class,
        'general' => Pages\Settings\General::class, // [tl! focus]
        'inventory-index' => Pages\Settings\Inventories\Browse::class,
        // ...
    ];
    
    'components' => [
        // ...
    ],
];

```

<div class="screenshot" aria-hidden="true">
  <img src="/screenshots/{{version}}/setting-general.png" alt="Update store setting">
  <div class="caption">General setting</div>
</div>

In this interface you will update your store. Don't forget that the model used is the model `Shopper\Core\Models\Setting`.
With the Shopper configuration you can completely change the architecture of this view and the data stored in the database.
