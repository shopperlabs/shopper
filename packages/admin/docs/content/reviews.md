# Reviews

Coming Soon...


### Model

The model used is `Shopper\Models\Review`.

| Name             | Type     | Required | Notes                      |
|------------------|----------|----------|----------------------------|
| `id`             | autoinc  |          | auto                       |
| `approved`       | boolean  | no       | Default `false`            |
| `is_recommended` | boolean  | no       | Default `false`            |
| `title`          | string   | no       | The title of the review.   |
| `content`        | longText | no       | The content of the review. |
| `rating`         | string   | yes      | The rating of the review   |

### Components

By default, review Livewire components are not published. To customize components, you must publish them.

```bash
php artisan shopper:component:publish review
```

This command will publish all Livewire components used for review management (from pages to form components).
Once you've published the component, you can find it in the `review.php` locate in the `config/shopper/components` folder.

```php
use Shopper\Livewire;

return [
  'pages' => [
        'review-index' => Livewire\Pages\Reviews\Index::class,
    ],
    
    'components' => [
        'slide-overs.review-detail' => Livewire\SlideOvers\ReviewDetail::class,
    ],
];
```
