# Discounts

...

### Fields

The model used is `Shopper\Core\Models\Discount`.

| Name                   | Type     | Required | Notes                                                                                           |
|------------------------|----------|----------|-------------------------------------------------------------------------------------------------|
| `id`                   | autoinc  |          | auto                                                                                            |
| `code`                 | string   | yes      | The given code for the discount                                                                 |
| `type`                 | string   | yes      | The type of discount `Shopper\Core\Enum\DiscountType`                                           |
| `value`                | int      | yes      | Depends on the type of discount you want to apply. It can be a percentage or a fixed amount     |
| `is_active`            | boolean  | no       | Defines the visibility of the discount for customers.                                           |
| `apply_to`             | string   | yes      | Defines what the discount can be applied to `Shopper\Core\Enum\DiscountApplyTo`                 |
| `min_required`         | string   | yes      | Defines the conditions required to apply the discount `Shopper\Core\Enum\DiscountRequirement`   |
| `min_required_value`   | string   | no       | The minimum value required after defining the required condition, default `NULL`                |
| `eligibility`          | string   | yes      | Defines discount eligibility conditions `Shopper\Core\Enum\DiscountEligibility`                 |
| `usage_limit`          | int      | no       | How many uses the discount has had                                                              |
| `usage_limit_per_user` | boolean  | no       | Defines whether the coupon can be used more than once by customers                              |
| `total_use`            | int      | no       | The number of times the discount has been used, default `0`                                     |
| `start_at`             | datetime | yes      | The datetime the discount starts                                                                |
| `end_at`               | datetime | no       | The datetime the discount expires, if `NULL` it won't expire                                    |
| `metadata`             | array    | no       | `NULL`, json column to save any data key:value                                                  |
| `zone_id`              | int      | no       | The area in which the discount can be applied. If `NULL`, the discount can be applied anywhere. |

### Components

By default, discounts Livewire components are not published. To customize components, you must publish them.

```bash
php artisan shopper:component:publish discount
```

This command will publish all Livewire components used for discount management (from pages to form components).
Once you've published the component, you can find it in the `discount.php` locate in the `config/shopper/components` folder.

```php
use Shopper\Livewire;

return [
  'pages' => [
        'discount-index' => Livewire\Pages\Discount\Index::class,
    ],
    
    'components' => [
        'slide-overs.discount-form' => Livewire\SlideOvers\DiscountForm::class,
    ],
];
```

## Manage Discount
