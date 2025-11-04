# Reviews

Coming Soon...


### Model

The model used is `Shopper\Core\Models\Review`.

| Name             | Type     | Required       | Notes                                                                   |
|------------------|----------|----------------|-------------------------------------------------------------------------|
| `id`             | autoinc  |                | auto                                                                    |
| `approved`       | boolean  | no             | Default `false`                                                         |
| `is_recommended` | boolean  | no             | Default `false`                                                         |
| `title`          | string   | no             | The title of the review.                                                |
| `content`        | longText | author_type no | The content of the review.                                              |
| `rating`         | string   | yes            | The rating of the review                                                |
| `reviewrateable` | morphs   | yes            | relation generate `reviewrateable_id` and `reviewrateable_type` columns |
| `author`         | morphs   | yes            | relation generate `author_id` and `author_type` columns                 |

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
