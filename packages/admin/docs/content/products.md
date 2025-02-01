# Products

Coming Soon...


### Model

The model used is `Shopper\Models\Product`.

| Name              | Type        | Required | Notes                                                                        |
|-------------------|-------------|----------|------------------------------------------------------------------------------|
| `id`              | autoinc     |          | auto                                                                         |
| `name`            | string      | yes      | The name of the product.                                                     |
| `slug`            | string      | yes      | Unique, default value is auto generated using product name                   |
| `sku`             | string      | no       | The Stock Keeping Unit (SKU) code of the product                             |
| `barcode`         | string      | no       | The barcode of the product.                                                  |
| `type`            | ProductType | no       | The type of product` Shopper\Core\Enum\ProductType`                          |
| `is_visible`      | boolean     | no       | Defines the visibility of the product for customers                          |
| `featured`        | boolean     | no       | Default `false`                                                              |
| `weight_unit`     | Weight      | no       | The weight unit of the product                                               |
| `weight_value`    | float       | no       | The weight value of the product                                              |
| `height_unit`     | Length      | no       | The height unit of the product                                               |
| `height_value`    | float       | no       | The height value of the product                                              |
| `width_unit`      | Length      | no       | The width unit of the product                                                |
| `width_value`     | float       | no       | The width value of the product                                               |
| `depth_unit`      | Length      | no       | The depth unit of the product                                                |
| `depth_value`     | float       | no       | The depth value of the product                                               |
| `volume_unit`     | Volume      | no       | The volume unit of the product                                               |
| `volume_value`    | float       | no       | The volume value of the product                                              |
| `security_stock`  | int         | no       | Define the security stock of the product.                                    |
| `variants_stock`  | int         | no       | If the product is a variant, it defines the  stock for variants              |
| `seo_title`       | string      | no       | Nullable,                                                                    |
| `seo_description` | string      | no       | Nullable,                                                                    |
| `external_id`     | string      | no       | Nullable,                                                                    |
| `published_at`    | datetime    | yes      | Defines a publication date so that your product are scheduled on your store. |
| `metadata`        | array       | no       | Nullable,                                                                    |

:::tip
Models are customizable, and we recommend changing the **Product** model when you configure your site.
To change the model you need to look at the configuration file `config/shopper/models.php`.
:::

```php
use Shopper\Core\Models;

return [
    // ...
    'product' => Models\Product::class,
];
```

1. Create your own Model
    ```bash
    php artisan make:model Product
    ```
   Once the `app/Models/Product.php` model is created in your app folder, you need to extend from the `Shopper\Core\Models\Product` Model.

2. Extend your Product model from the Product Shopper Model
    ```php
    namespace App\Models;

    use Shopper\Core\Models\Product as Model;

    class Product extends Model
    {
    }
    ```

3. Update `product` key for the model on the `models.php` config file to use our new model
    ```php
    'product' => Models\Product::class, // [tl! --]
    'product' => \App\Models\Product::class, // [tl! ++]
    ```

### Components

By default, product Livewire components are not published. To customize components, you must publish them.

```bash
php artisan shopper:component:publish product
```

This command will publish all Livewire components used for product management (from pages to form components).
Once you've published the component, you can find it in the `product.php` locate in the `config/shopper/components` folder.

```php
use Shopper\Livewire;

return [
  'pages' => [
        'product-index' => Livewire\Pages\Product\Index::class,
        // ...
    ],
    
  'components' => [
        // ...
        'slide-overs.add-product' => Livewire\SlideOvers\AddProduct::class,
        // ...
  ],
];
```
