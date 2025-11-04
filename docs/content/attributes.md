# Attributes

Attributes are crucial for creating variable products. Attributes can also be used to enhance the product filtering and selection process, 
allowing customers to find and select products that meet their specific preferences or needs.

An Attribute is a specification or characteristic of a product, for example, Color, Size, and Pattern are attributes of a T-Shirt.
You can also create many attributes for a single product.

In Shopper, attributes go beyond basic product descriptions—they highlight the unique value and features of each item, 
helping customers understand what makes a product special. By leveraging attributes, you can create variable products, 
enhance search and filtering options, and provide a more personalized shopping experience.

<div class="screenshot">
    <img src="/screenshots/{{version}}/attributes.png" alt="Attributes">
    <div class="caption">Attributes</div>
</div>

## Models

- [Attribute](#attribute)(`Shopper\Core\Models\Attribute`) represents characteristics of a product, such as **Size**, **Color**, **Material**, or any other feature that helps differentiate products
- [AttributeValue](#attributevalue)(`Shopper\Core\Models\AttributeValue`) Model stores the specific values associated with an attribute. For example, if the attribute is "Color," the values might include "Red," "Blue," and "Green."
- [AttributeProduct](#attributeproduct)(`Shopper\Core\Models\AttributeProduct`) Model acts as a pivot table that connects attributes and their values to specific products. This model is essential for creating variable products and managing product variations.

### Attribute

| Name            | Type    | Required | Notes                                                     |
|-----------------|---------|----------|-----------------------------------------------------------|
| `id`            | autoinc |          | auto                                                      |
| `name`          | string  | yes      |                                                           |
| `slug`          | string  | no       | `Unique`, default value is generated using attribute name |
| `icon`          | string  | no       |                                                           |
| `description`   | string  | no       |                                                           |
| `type`          | string  | yes      | `[FieldType](#attribute-types)`                           |
| `is_enabled`    | bool    | no       | Default `false`                                           |
| `is_searchable` | bool    | no       | Default `false`                                           |
| `is_filterable` | bool    | no       | Default `false`                                           |

### AttributeValue

| Name           | Type    | Required | Notes                                                 |
|----------------|---------|----------|-------------------------------------------------------|
| `id`           | autoinc |          | auto                                                  |
| `value`        | string  | yes      |                                                       |
| `key`          | string  | yes      | `Unique`                                              |
| `position`     | int     | no       | Default `1`                                           |
| `attribute_id` | int     | yes      | int (`Attribute` object via the `attribute` relation) |

### AttributeProduct

| Name                      | Type    | Required | Notes                                                  |
|---------------------------|---------|----------|--------------------------------------------------------|
| `id`                      | autoinc |          | auto                                                   |
| `attribute_id`            | int     | yes      | int (`Attribute` object via the `attribute` relation)  |
| `product_id`              | int     | yes      | int (`Product` object via the `product` relation)      |
| `attribute_value_id`      | int     | no       | int (`AttributeValue` object via the `value` relation) |
| `attribute_custome_value` | string  | no       | Custom value for attribute with no value id            |

## Attribute Types

Attributes can be of several types. And this allows you to define how they will be displayed when assigned to a product.
The types are available via the `Shopper\Core\Enum\FieldType` Enum class and the available values are:

- `FieldType::Checkbox()` It represents multiple values that can be assigned to a product.
- `FieldType::ColorPicker()` Multiple values as checkbox but for the color.
- `FieldType::DatePicker()` Displays a date value in the preferred format.
- `FieldType::RichText()` Rich Text Editor.
- `FieldType::Select()` Displays an option to select a value.
- `FieldType::Text()` A single-line input field for text.
- `FieldType::Number()` Integer or Decimal value.

## Create Attribute

On the sidebar, navigate to **Products > Attributes** and Click on the “Create” button on the attributes page to display the form.

<div class="screenshot">
  <img src="/screenshots/{{version}}/attribute-form.png" alt="create attribute">
  <div class="caption">Create Attribute</div>
</div>

Fill in your information and save the form to return to the list of attributes. Required fields are marked with an asterisk (*).
If you use another interface (e.g. API) to save your Attribute, you can save directly using the Attribute Model

```php
use Shopper\Core\Models\Attribute;
use Shopper\Core\Enum\FieldType;

$attribute = Attribute::create([
    'name' => 'Color',
    'slug' => 'color',
    'type' => FieldType::Checkbox(),
    'is_enabled'=> true,
]);
```

