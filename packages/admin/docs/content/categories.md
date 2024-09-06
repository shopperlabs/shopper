# Categories

Categories are the primary way to group products with similar features. You can also add subcategories if desired.

For example, if you sell clothing, you might have “t-shirts”, “hoodies” and “pants” as categories.

## Overview

Shopper gives you a possibility to categorize your products in a very flexible way, which is one of the most vital functionalities of the modern e-commerce systems.
The categories system in Shopper use the [Laravel Adjacency List](https://github.com/staudenmeir/laravel-adjacency-list) package to create categories trees like this

```plain theme:github-light
Category
 |
 |\__ Women
 |       \_ T-Shirts
 |       \_ Shirts
 |       \_ Dresses
 |       \_ Shoes
 |
 |\__ Men
 |      \_ Accessories
 |      |--> Bags
 |      |--> Jeans
 |      |--> Sunglasses
 |      \_ Jeans
 |      \_ T-shirts
 |      \_ Shoes
 |
```

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/categories.png" alt="Categories">
  <div class="caption">Categories</div>
</div>

### Fields

The model used is `Shopper\Core\Models\Category`.

| Name              | Type     | Required | Notes                                                  |
|-------------------|----------|----------|--------------------------------------------------------|
| `id`              | autoinc  |          | auto                                                   |
| `name`            | string   | yes      |                                                        |
| `slug`            | string   | yes      | Unique, default value is generated using category name |
| `description`     | longText | no       | Nullable                                               |
| `position`        | string   | no       | Default `0`                                            |
| `is_enabled`      | boolean  | no       | Default `false`                                        |
| `seo_title`       | string   | no       | Nullable, for seo title max length is 60               |
| `seo_description` | string   | no       | Nullable, for seo description max length is 160        |
| `parent_id`       | bigint   | no       |                                                        |

:::tip
Models are customizable, and we recommend changing the **Category** model when you configure your store.
To change the model you need to look at the configuration file `config/shopper/models.php`.
:::

Let's keep in mind the modification that was made in the previous section regarding [Brands](/brands).

```php
use Shopper\Core\Models;

return [
    // ...
    'brand' => \App\Models\Brand::class,

    // ...
    'category' => Models\Category::class, // [tl! focus]
];
```

1. Create your own model that you have to use
    ```bash
    php artisan make:model Category
    ```
    Once the `app/Models/Category.php` model is created in our app folder, we will make it extend from the `Shopper\Core\Models\Category` model available in Shopper.

2. Extend our Category model from the Category Shopper Model
    ```php
    namespace App\Models;

    use Shopper\Core\Models\Category as Model;

    class Category extends Model
    {
    }
    ```

3. Update `category` key for the model on the `shopper/models.php` config file to use our new model
    ```php
    'category' => Models\Category::class, // [tl! --]
    'category' => \App\Models\Category::class, // [tl! ++]
    ```

### Components

By default, categories Livewire components are not published. To customize components, you must publish them.

```bash
php artisan shopper:component:publish category
```

```php
use Shopper\Livewire;

return [

    'pages' => [
        'category-index' => Livewire\Pages\Category\Index::class,
    ];
  
    'components' => [
        'slide-overs.category-form' => Livewire\SlideOvers\CategoryForm::class,
        'slide-overs.re-order-categories' => Livewire\SlideOvers\ReOrderCategories::class,
    ],
];
```

## Manage Categories

Categories are determinant of how people will navigate on your site and search for your products. You should focus on your category tree and how categories are organized even before you start creating product sheets.

The categories are accessible via the **Categories** Menu on the left sidebar. The display page is rendered by the Livewire component `Shopper\Framework\Http\Livewire\Categories\Browse` and for the display of the categories table is the component `Shopper\Framework\Http\Livewire\Tables\CategoriesTable`.

You can modify them in the component configuration file to use your own.

### Create category

Click on the "Create" button on the categories page, and the form display.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/category-create.png" alt="Create category form">
  <div class="caption">Create category</div>
</div>

Save your changes in order to be taken back to the categories list. Required fields are marked with an **asterisk (*)**
The SEO section allows you to define how your category information should be displayed in search engines.
Once you have finished configuring your category, save it, and you are ready to fill it with products.

If you use another interface (e.g. API) to save your categories, you can save directly using your Model

```php
use App\Models\Category;

$category = Category::create([
  'name' => $name = 'Clothes',
  'slug' => $name,
  'is_enabled' => true,
]);
```

The slug cannot be null, you have to fill in the value of the category name and according to that the slug will be generated.
In case a slug already exists, the slug will be automatically extended to prevent duplicates:

```php
use App\Models\Category;

$category1 = Category::create(['name' => 'Category', 'slug' => 'Category']);
$category2 = Category::create(['name' => 'Category', 'slug' => 'Category']);

echo $category1->slug;
// category

echo $category2->slug;
// category-1
```

And if the category has a parent, the child's slug will be generated with the parent's directly
This generation is done when adding a category in Shopper. But you can change this behavior by extending the category create [Shopper\Framework\Http\Livewire\Categories\Create](https://github.com/shopperlabs/framework/blob/main/src/Http/Livewire/Categories/Create.php) Livewire component or by creating a new one.

```php
use App\Models\Category;

$category = Category::create(['name' => 'Photo', 'slug' => 'photo']);
$categoryChild = Category::create([
  'name' => $name = 'Camera',
  'slug' => $this->parent ? $this->parent->slug . '-' . $name : $name,
  'parent_id' => $caregory->id
]);

echo $category->slug;
// photo

echo $categoryChild->slug;
// photo-camera
```

#### Create Category with parent

```php
use App\Models\Category;

$parent = Category::create([
  'name' => $name = 'Clothes',
  'slug' => $name,
  'is_enabled' => true,
]);

$child = Category::create([
  'name' => 'Jeans',
  'slug' => 'jeans',
  'parent_id' => $parent->id, // [tl! focus]
  'is_enabled' => true,
]);
```

### Update category

The "Update" button allows you to modify the category.

:::info
It is important to know that if you update the category name, the slug will automatically be updated as well.
:::

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/category-update.png" alt="update category">
  <div class="caption">Update Category</div>
</div>

## Retrieve Data

With Shopper, you are the master of your front-end. After extending the model you can make all the necessary queries to retrieve your data.
We just recommend that you always use the `enabled` scope to ensure that only active categories are visible

```php
use App\Models\Category;

$categories = Category::enabled()->get(),
```

To find a category with his children or parent, etc. The following functions are available.

- `ancestors()`: The model's recursive parents.
- `ancestorsAndSelf()`: The model's recursive parents and itself.
- `bloodline()`: The model's ancestors, descendants and itself.
- `children()`: The model's direct children.
- `childrenAndSelf()`: The model's direct children and itself.
- `descendants()`: The model's recursive children.
- `descendantsAndSelf()`: The model's recursive children and itself.
- `parent()`: The model's direct parent.
- `parentAndSelf()`: The model's direct parent and itself.
- `rootAncestor()`: The model's topmost parent.
- `siblings()`: The parent's other children.
- `siblingsAndSelf()`: All the parent's children.

```php
$ancestors = Category::find($id)->ancestors;
$categories = Category::with('descendants')->get();
$categories = Category::whereHas('siblings', function ($query) {
  $query->where('name', '=', 'Photo');
})->get();
$total = Category::find($id)->descendants()->count();
Category::find($id)->descendants()->update(['is_enabled' => false]);
Category::find($id)->siblings()->delete();
```

The complete documentation is available in the readme of [Laravel Adjacency List](https://github.com/staudenmeir/laravel-adjacency-list)

## Disabled Category

It may be that your store sells only one type of product, and that you don't need to categorize them (maybe it's just keyboards).
If you don't want to use the functionalities attached to categories and simplify your administration interface, you can simply deactivate the categories feature.

This will hide categories on the sidebar and disabled all categories-related functionalities in your store.
To disable categories-related functionalities, open the `features.php` configuration file in the `config/shopper` folder and set the category key to disable.

```php
use Shopper\Enum\FeatureState;

return [
    'attribute' => FeatureState::Enabled,
    'brand' => FeatureState::Enabled,
    'category' => FeatureState::Enabled, // [tl! --]
    'category' => FeatureState::Disabled, // [tl! ++]
    'collection' => FeatureState::Enabled,
    'discount' => FeatureState::Enabled,
    'review' => FeatureState::Enabled,
];
```
