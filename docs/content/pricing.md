# Pricing

Coming Soon...


### Model

The model used is `Shopper\Core\Models\Price`.

| Name             | Type    | Required | Notes                                                         |
|------------------|---------|----------|---------------------------------------------------------------|
| `id`             | autoinc |          | auto                                                          |
| `amount`         | int     | no       | Nullable                                                      |
| `compare_amount` | int     | no       | Nullable                                                      |
| `cost_amount`    | int     | no       | Nullable                                                      |
| `priceable`      | morphs  | yes      | Relation generate `priceable_id` and `priceable_type` columns |
| `currency_id`    | int     | yes      | int (`Currency` object via the `currency` relation)           |


### Components

By default, pricing Livewire components are not published. To customize components, you must publish them.

```bash
php artisan shopper:component:publish product
```

This command will publish all Livewire components used for product and price management (from pages to form components).
Once you've published the component, you can find it in the `product.php` locate in the `config/shopper/components` folder.
```php
use Shopper\Livewire;

return [
  'components' => [
        // ...
        'products.pricing' => Components\Products\Pricing::class,
        'slide-overs.manage-pricing' => Livewire\SlideOvers\ManagePricing::class,
        // ...
  ],
];
```
