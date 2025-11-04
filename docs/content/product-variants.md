# Product Variants

### Model

The model used is `Shopper\Core\Models\ProductVariant`.

| Name              | Type    | Required | Notes                                                                             |
|-------------------|---------|----------|-----------------------------------------------------------------------------------|
| `id`              | autoinc |          | auto                                                                              |
| `name`            | string  | yes      | The name of the product variant.                                                  |
| `sku`             | string  | no       | Nullable, Unique, the Stock Keeping Unit (SKU) code of the product variant        |
| `barcode`         | string  | no       | Nullable, Unique, the barcode of the product variant.                             |
| `ean`             | string  | no       | Nullable, Unique, the european article numbering                                  |
| `upc`             | string  | no       | Nullable, Unique, the code universel des produits                                 |
| `allow_backorder` | boolean | yes      | Default `false`                                                                   |
| `position`        | int     | yes      | Default `1`                                                                       |
| `product_id`      | int     | yes      | Int (`Product` object via the `product` relation)                                 |
| `weight_unit`     | string  | yes      | The weight unit of the product `Shopper\Core\Enum\Dimension\Weight`, default `kg` |
| `weight_value`    | float   | no       | The weight value of the product, default `0.00`                                   |
| `height_unit`     | string  | yes      | The height unit of the product `Shopper\Core\Enum\Dimension\Length`, default `cm` |
| `height_value`    | float   | no       | The height value of the product, default `0.00`                                   |
| `width_unit`      | string  | yes      | The width unit of the product `Shopper\Core\Enum\Dimension\Length`, default `cm`  |
| `width_value`     | float   | no       | The width value of the product, default `0.00`                                    |
| `depth_unit`      | string  | yes      | The depth unit of the product `Shopper\Core\Enum\Dimension\Length`, default `cm`  |
| `depth_value`     | float   | no       | The depth value of the product, default `0.00`                                    |
| `volume_unit`     | string  | yes      | The volume unit of the product `Shopper\Core\Enum\Dimension\Volume`, default `l`  |
| `volume_value`    | float   | no       | The volume value of the product, default `0.00`                                   |
| `metadata`        | array   | no       | Nullable,                                                                         |

:::tip
Models are customizable, and we recommend changing the **ProductVariant** model when you configure your site.
To change the model you need to look at the configuration file `config/shopper/models.php`.
:::

```php
use Shopper\Core\Models;

return [
    // ...
    'variant' => Models\ProductVariant::class, // [tl! ++]
];
```

1. Create your own Model
    ```bash
    php artisan make:model ProductVariant
    ```
   Once the `app/Models/ProductVariant.php` model is created in your app folder, you need to extend from the `Shopper\Core\Models\ProductVariant` Model.

2. Extend your `ProductVariant` model from the `ProductVariant` Shopper Model
    ```php
    namespace App\Models;

    use Shopper\Core\Models\ProductVariant as Model;

    class ProductVariant extends Model
    {
    }
    ```

3. Use `variant` key for the model on the `models.php` config file to use our new model
    ```php
    'variant' => \App\Models\ProductVariant::class, // [tl! ++]
    ```

### Components

By default, product variant Livewire components are not published. To customize components, you must publish them.

```bash
php artisan shopper:component:publish product
```

This command will publish all Livewire components used for product management (from pages to form components).
Once you've published the component, you can find it in the `product.php` locate in the `config/shopper/components` folder.

```php
use Shopper\Livewire;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'product-index' => Livewire\Pages\Product\Index::class,
        'product-edit' => Livewire\Pages\Product\Edit::class,
        'variant-edit' => Livewire\Pages\Product\Variant::class, // [tl! focus]
        'attribute-index' => Livewire\Pages\Attribute\Browse::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'products.form.attributes' => Components\Products\Form\Attributes::class,
        'products.form.edit' => Components\Products\Form\Edit::class,
        'products.form.media' => Components\Products\Form\Media::class,
        'products.form.files' => Components\Products\Form\Files::class,
        'products.form.inventory' => Components\Products\Form\Inventory::class,
        'products.form.related-products' => Components\Products\Form\RelatedProducts::class,
        'products.form.seo' => Components\Products\Form\Seo::class,
        'products.form.shipping' => Components\Products\Form\Shipping::class,
        'products.form.variants' => Components\Products\Form\Variants::class, // [tl! focus]
        'products.variant-stock' => Components\Products\VariantStock::class, // [tl! focus]
        'products.type-configuration' => Components\Products\ProductTypeConfiguration::class,
        'products.pricing' => Components\Products\Pricing::class,

        'modals.related-products-list' => Livewire\Modals\RelatedProductsList::class,

        'slide-overs.add-product' => Livewire\SlideOvers\AddProduct::class,
        'slide-overs.add-variant' => Livewire\SlideOvers\AddVariant::class, // [tl! focus]
        'slide-overs.update-variant' => Livewire\SlideOvers\UpdateVariant::class, // [tl! focus]
        'slide-overs.generate-variants' => Livewire\SlideOvers\GenerateVariants::class, // [tl! focus]
        'slide-overs.attribute-form' => Livewire\SlideOvers\AttributeForm::class,
        'slide-overs.choose-product-attributes' => Livewire\SlideOvers\ChooseProductAttributes::class,
        'slide-overs.attribute-values' => Livewire\SlideOvers\AttributeValues::class,
        'slide-overs.manage-pricing' => Livewire\SlideOvers\ManagePricing::class,
    ],

];
```
