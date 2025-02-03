# Pricing

Coming Soon...


### Model

The model used is `Shopper\Models\Price`.

| Name             | Type    | Required | Notes                                                         |
|------------------|---------|----------|---------------------------------------------------------------|
| `id`             | autoinc |          | auto                                                          |
| `amount`         | int     | no       | Nullable                                                      |
| `compare_amount` | int     | no       | Nullable                                                      |
| `cost_amount`    | int     | no       | Nullable                                                      |
| `priceable`      | morphs  | yes      | Relation generate `priceable_id` and `priceable_type` columns |
| `currency_id`    | int     | yes      | int (`Currency` object via the `currency` relation)           |

:::tip
Models are customizable, and we recommend adding the **Price** model when you configure your site.
To add the model you need to look at the configuration file `config/shopper/models.php`.
:::

1. Create your own Model
    ```bash
    php artisan make:model Price
    ```
   Once the `app/Models/Price.php` model is created in your app folder, you need to extend from the `Shopper\Core\Models\Price` Model.

2. Extend our Price model from the Price Shopper Model
    ```php
    namespace App\Models;

    use Shopper\Core\Models\Price as Model;

    class Price extends Model
    {
    }
    ```

3. Add `price` key for the model on the `models.php` config file to use our new model
    ```php
    'price' => \App\Models\Price::class, // [tl! ++]
    ```
