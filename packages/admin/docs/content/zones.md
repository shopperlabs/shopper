# Zones

## Model

The model used is `Shopper\Core\Models\Zone`.

| Name          | Type    | Required | Notes                                                   |
|---------------|---------|----------|---------------------------------------------------------|
| `id`          | autoinc |          | auto                                                    |
| `name`        | string  | yes      |                                                         |
| `slug`        | string  | no       | Unique, default value is auto generated using zone name |
| `code`        | string  | no       | Unique, Nullable                                        |
| `is_enabled`  | boolean | no       | Default `false`                                         |
| `metadata`    | array   | no       | Nullable                                                |
| `currency_id` | int     | yes      | Int (`Currency` object via the `currency` relation)     |

## Components

The components used to manage zones are found in the component configuration file `config/shopper/components/setting.php`. This configuration file is not available by default.

But if you want to update or change the settings components, you can publish them with the command

```bash
php artisan shopper:component:publish setting
```
This file contains all Livewire components for settings. Here is only the list of zone components

```php
use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;

return [

    'pages' => [
        // ...
        'zones' => Pages\Settings\Zones::class,
        // ...
    ];
    
    'components' => [
        // ...
        'settings.zones.detail' => Components\Settings\Zones\Detail::class,
        'settings.zones.shipping-options' => Components\Settings\Zones\ZoneShippingOptions::class,
        // ...
    ],

];
```
You can expand each of its components to customize this section or replace the entire section if your system requires it.

You can also change the views too and return your own views.
