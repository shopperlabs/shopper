# Attributes

Attributes can be associated to Eloquent models to allow custom data to be stored.

Typically, these will be used the most with Products where different information is needed to be stored and presented to visitors.

For example, a shoes might have the following attributes assigned :
* Size
* Color

## Overview

<div class="screenshot">
  <img src="/screenshots/{{version}}/attributes.png" alt="Attributes">
  <div class="caption">Attributes</div>
</div>

### Fields

* `Shopper\Core\Models\Product`
* `Shopper\Core\Models\AttributeValue`
* `Shopper\Core\Models\AttributeProduct`

**Attribute Model**

| Name            | Type      | Required | Notes                                                         |
|-----------------|-----------|----------|---------------------------------------------------------------|
| `id`            | autoinc   |          | auto                                                          |
| `name`          | string    | yes      |                                                               |
| `slug`          | string    | yes      | Unique, default value is generated using category name        |
| `description`   | longText  | no       | Nullable                                                      |
| `type`          | FieldType | yes      | The field type to be used, e.g. `Shopper\Core\Enum\FieldType` |
| `is_enabled`    | boolean   | no       | Default `false` defines whether the attribute is active       |
| `is_searchable` | boolean   | no       | Default `false`  define if the attribute can be searched for  |
| `is_filterable` | boolean   | no       | Default `false` defines whether the attribute is filtered     |
| `icon`          | string    | no       | Define the icon associated with the attribute                 |

**AttributeValue Model**

| Name       | Type    | Required | Notes       |
|------------|---------|----------|-------------|
| `id`       | autoinc |          | auto        |
| `value`    | string  | yes      |             |
| `key`      | string  | yes      |             |
| `position` | int     | yes      | Default `1` |

**AttributeProduct Model**

| Name                     | Type     | Required | Notes |
|--------------------------|----------|----------|-------|
| `id`                     | autoinc  |          | auto  |
| `product_id`             | int      | yes      |       |
| `attribute_value_id`     | int      | yes      |       |
| `attribute_custom_value` | longtext | no       |       |


### Components

By default, categories Livewire components are not published. To customize Attribute components, you must publish them with this command.

```bash
php artisan shopper:component:publish product
```
`config\shopper\components\product.php`
```php
use Shopper\Livewire;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        //...
        'attribute-index' => Livewire\Pages\Attribute\Browse::class, // [tl! focus]
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        //....
        'slide-overs.attribute-form' => Livewire\SlideOvers\AttributeForm::class, // [tl! focus]
    ],
```


### Manage Attributes
Attributes are essential for describing and differentiating your products. They enable customers to filter and search for items according to specific characteristics, such as size, color or material. By clearly organizing these attributes, you improve the user experience and make your site easier to navigate.

### Create Attribute

Click on the “Create” button on the attributes page to display the form.

<div class="screenshot">
  <img src="/screenshots/{{version}}/attribute-create.png" alt="create-attribute">
  <div class="caption">Create Attribute</div>
</div>

Fill in your information and save the form to return to the list of attributes. Required fields are marked with an asterisk (*).

If you use another interface (e.g. API) to save your attribute, you can save directly using your Model

```php
use App\Models\Attribute;

$attribute = Attribute::create([
    'name' => 'Color',
    'slug' => 'color',
    'type' => 'checkbox',
    'is_enabled'=> true
]);
```

### Edit Attribute

In some cases, we may wish to modify attribute information. In this case, it is possible to do so via the edit form displayed when the edit button corresponding to the attribute line is clicked.

:::info
It is important to know that if you update the attribute name, the slug will automatically be updated as well.
:::

<div class="screenshot">
  <img src="/screenshots/{{version}}/attribute-update.png" alt="Attributes">
  <div class="caption">Edit Attribute</div>
</div>


Once you have made your changes, click on the save button to save them.

###  Attributes Values
Attribute values are associated with model attributes.

These attribute values are mainly used for products in order to differentiate a product according to these attributes.

## Overview

<div class="screenshot">
  <img src="/screenshots/{{version}}/attribute-values.png" alt="Attributes">
  <div class="caption">Attribute values</div>
</div>

### Manage Attribute value

Attribute values are crucial for providing accurate information about your products. They represent the specific options available for each attribute, such as "S," "M," "L" for size, or "Red," "Blue," "Green" for color. By clearly defining these values, you help customers make informed choices and quickly find the product that meets their needs.

### Create Attribute value

Click on the values button corresponding to the attribute line, then click on the new value button to display the form.

<div class="screenshot">
  <img src="/screenshots/{{version}}/attribute-values-create.png" alt="create-attribute">
  <div class="caption">Create Attribute value</div>
</div>

Fill in and submit your information by clicking on the submit button. Your value will automatically be associated with this attribute.

If you use another interface (e.g. API) to save your attributeValue, you can save directly using your Model

```php
use App\Models\Attribute;
use App\Models\AttributeValue;

$attribute = Attribute::create([
    'name' => 'Color',
    'slug' => 'color',
    'type' => 'checkbox',
    'is_enabled'=> true
]);

$redAttributeValue = AttributeValue::create([
    'value' => 'Red',
    'key' => '#1e3a8a',
    'attribute_id' => $attribute->id // [tl! focus]
]);
 ```
### Edit Attribute value

In some cases, we may wish to modify attribute value information. In this case, it is possible to do so via the edit form displayed when the edit button corresponding to the attribute value line is clicked.

<div class="screenshot">
  <img src="/screenshots/{{version}}/attribute-values-edit.png" alt="Attributes">
  <div class="caption">Edit Attribute value</div>
</div>


Once you have made your changes, click on the submit button to save them.


## Retrieve Data

with shopper, if you want to retrieve products in descending order with their attributes, you can do it this way.

```php
use App\Models\Product;

Product::with(['options'])
    ->scopes(['publish'])
    ->latest()
```
