# Collections

Collections, although not strictly the same, are akin to Categories. They serve to allow you to add products, either explicitly or via certain criteria, for use on your store.

In most e-commerce tools, collections are considered as categories. And especially on Shopify, collections are a great feature for grouping products.

And for the constitution of the collections we got closer to what Shopify offers in terms of configuration, and the management of collections in Shopper is inspired from Shopify.

## Context

For example if you have a store that sells various electronic products, you will probably have categories like "Phones", "Computers", "Cameras" etc. Each of these categories may have several products that can be listed.

And to try to group the products in an even more detailed way you can create for example a collection called "Gaming Collection" and specify in this collection that any product with certain conditions can be included.

And that Gaming Collection can have products that even come from various categories in your site (desktop, laptop, monitor, and many others of the same type) or that don't even have categories (very rare case in e-commerce sites).

Let's use Netflix as an example. Categories are essentially genres: adventure, action, horror, romance, etc., while collections are similar to a TV series or movie sequels that are ultimately meant to be viewed together.

## Collections vs Categories

The question is essential, it is difficult to find this type of configuration on e-commerce tools because most of the time categories, collections or taxonomies are used to perform the same action **group products**.

The advantage of having collections in addition to categories in Shopper is to differentiate or optimize the search for products by customers in your store.

### Depth

A collection can't have a child or a parent like a category. So all the collections are at the same hierachical level.

### Condition

Where products can be added to any category, collections cannot. Depending on the type of collection you want to create (Manual or Automatic) you will find yourself creating conditions or rules for the products that should be in that collection.

## Overview

As mentioned above, the collections in Shopper are inspired by [Shopify collections](https://help.shopify.com/en/manual/products/collections). So there are also 2 types of collections: "Manual" and "Automatic" and the configuration for each is different.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/collections.png" alt="Collections">
  <div class="caption">Collections</div>
</div>

### Fields

As the collections can be automatic, they are managed by 2 Models, the Collection model and the model for rules associated with automatic collections.

Manual collections do not need to have rules.

- Collection model is `Shopper\Core\Models\Collection`.
- Rule model is `Shopper\Core\Models\CollectionRule`

**Collection Model**

| Name               | Type       | Required | Notes                                                                                                          |
|--------------------|------------|----------|----------------------------------------------------------------------------------------------------------------|
| `id`               | autoinc    |          | auto                                                                                                           |
| `name`             | string     | yes      |                                                                                                                |
| `slug`             | string     | no       | Unique, default value is generated using collection name                                                       |
| `description`      | longText   | no       | Nullable                                                                                                       |
| `type`             | enum       | yes      | Values `['manual', 'auto']`                                                                                    |
| `sort`             | string     | no       | Nullable, potential values `alpha_asc`, `alpha_desc`, `price_desc`, `price_asc`, `created_desc`, `created_asc` |
| `match_conditions` | enum       | no       | Nullable, `['all', 'any']`                                                                                     |
| `published_at`     | dateTimeTz | no       | Default `now()`                                                                                                |

**CollectionRule Model**

| Name            | Type    | Required | Notes                                                                                                                            |
|-----------------|---------|----------|----------------------------------------------------------------------------------------------------------------------------------|
| `id`            | autoinc |          | auto                                                                                                                             |
| `rule`          | string  | yes      | current values `product_title`, `product_price`, `compare_at_price`, `inventory_stock`, `product_brand`, `product_category`      |
| `operator`      | string  | yes      | current values `equals_to`, `not_equals_to`, `less_than`, `greater_than`, `starts_with`, `ends_with`, `contains`, `not_contains` |
| `value`         | string  | yes      |                                                                                                                                  |
| `collection_id` | bigint  | no       | Collection ID                                                                                                                    |

:::tip
Models are customizable, and we recommend changing the **Collection** model when you configure your store.
To change the model you need to look at the configuration file `config/shopper/models.php`.
:::

Let's keep in mind the modification that was made in the previous section regarding [Categories](/categories).

```php
use Shopper\Core\Models;

return [
    'category'  => \App\Models\Category::class,

    'collection'  => Models\Collection::class, // [tl! focus]
];
```

1. Create your own model that you have to use
    ```bash
    php artisan make:model Collection
    ```
    Once the `app/Models/Collection.php` model is created in our app folder, we will make it extend from the `Shopper\Core\Models\Collection` Model.

2. Extend our Collection model from the Collection Shopper Model
    ```php
    namespace App\Models;

    use Shopper\Core\Models\Collection as Model;

    class Collection extends Model
    {
    }
    ```

3. Update `collection` key for the model on the `models.php` config file to use our new model
    ```php
    'collection'  => Models\Collection::class, // [tl! --]
    'collection'  => \App\Models\Collection::class, // [tl! ++]
    ```

### Components

By default, collections Livewire components are not published. To customize components, you must publish them.

```bash
php artisan shopper:component:publish collection
```

This command will publish all Livewire components used for collection management (from pages to form components).
Once you've published the component, you can find it in the `collection.php` locate in the `config/shopper/components` folder.

```php
use Shopper\Livewire;

return [

    'pages' => [
        'collection-index' => Livewire\Pages\Collection\Index::class,
        'collection-edit' => Livewire\Pages\Collection\Edit::class,
    ],

    'components' => [
        'collections.products' => Livewire\Components\Collection\CollectionProducts::class,

        'modals.products-list' => Livewire\Modals\CollectionProductsList::class,

        'slide-overs.collection-rules' => Livewire\SlideOvers\CollectionRules::class,
        'slide-overs.add-collection-form' => Livewire\SlideOvers\AddCollectionForm::class,
    ],

];
```

## Manage Collections

Form your Shopper admin on the sidebar go to **Collections**. The display page is rendered by the Livewire component `Shopper\Livewire\Pages\Collection\Index::class`.

<div class="screenshot">
    <img src="/img/screenshots/{{version}}/collection-empty-state.png" alt="Collections empty data">
  	<div class="caption">Collections</div>
</div>

### Create collection

Click on the "Create" button on the collections page, which will display and slideover.
Save your changes in order to be taken back to the collection edit page. Required fields are marked with an **asterisk (*)**.

You can create two types of collections as we said: `Automatic` and `Manual` collection.
Only automatic collections have rules for automating them. When you choose to create an automatic collection the rules section will be available in the edit form.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/collection-rules.png" alt="automatic collection rules">
  <div class="caption">Automatic collection rules</div>
</div>

After you create a collection, you can't change its type.

## Retrieve Data

After extending (or not) the Shopper Collection Model you can use your own Model to retrieve data from the database. Here a code example.

```php
use App\Models\Collection;

// To retrieve only manual collections
$collections = Collection::manual()->get();
// To retrieve collection by slug
$collection = Collection::findBySlug('summers-clothes');
```

To view the image of a collection you can consult the [Media documentation](/media#retrieving-images). And you can display collections in your frontend.

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/collection-preview.png" alt="collections data">
  <div class="caption">Collections</div>
</div>

## Disabled Collection

Collections aren't necessarily easy for every store to adopt, so in some circumstances categories may suffice for grouping your products.
If you don't plan to use collections in your store, you can simply deactivate them.

To disable collections-related functionalities, open the `features.php` configuration file in the `config/shopper` folder and set the collection key to disable.

```php
use Shopper\Enum\FeatureState;

return [
    'attribute' => FeatureState::Enabled,
    'brand' => FeatureState::Enabled,
    'category' => FeatureState::Enabled,
    'collection' => FeatureState::Enabled, // [tl! --]
    'collection' => FeatureState::Disabled, // [tl! ++]
    'discount' => FeatureState::Enabled,
    'review' => FeatureState::Enabled,
];
```
