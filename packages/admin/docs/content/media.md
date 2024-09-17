# Media

Shopper supports assigning images to products, brands, collections and categories. It is an additional layer provided by the framework with the help of the [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary)
We recommend organizing your images in a folder offline and keeping a backup in case you need them in the future or mistakenly alter one and wish to revert to the original. You can look at the [documentation](/configuration#update-configurations) for this purpose

## Configuration

For uploading images we are using [FilePond](https://pqina.nl/) and some custom upload component with Livewire.

:::warning
To apply this action on your model you have to prepare your Model with the Laravel Media Library [configuration](https://spatie.be/docs/laravel-medialibrary/v10/basic-usage/preparing-your-model)
:::

## Media Variants

The Spatie Media library supports defining various image sizes, so-called [Conversions](https://spatie.be/docs/laravel-medialibrary/v10/converting-images/defining-conversions). The uploaded images will be then converted to the given sizes with the given parameters.

For the moment in Shopper for all the Model that's used Media Library the only conversion available is

```php
public function registerMediaConversions(?Media $media = null): void
{
    $this->addMediaConversion('thumb200x200')
        ->fit(Manipulations::FIT_CROP, 200, 200);
}
```

But you can extend the different models to add conversions according to your needs.

## Retrieving Images

### Thumbnail
The presence of thumbnails is a very common scenario, which is why Shopper use them.

```php
$product->getUrl('thumb200x200')
```

For more information on what's available, see [Defining conversions](https://spatie.be/docs/laravel-medialibrary/v10/converting-images/defining-conversions#content-using-multiple-conversions)

### Images
To get an image with full url on a product, a brand or a collection

```php
$product->getFirstMediaUrl(config('shopper.core.storage.disk_name'))
```
