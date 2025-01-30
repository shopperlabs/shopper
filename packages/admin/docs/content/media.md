# Media

Shopper supports assigning images to products, variants, brands, collections and categories. It is an additional layer provided 
by the framework with the help of the [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary)

## Configuration

The `config/shopper/media.php` file allows you to customize how media files are handled. Below are the available configuration options:

### Storage Disk

Specifies the storage disk used to save media files. By default, Shopper uses the `public` disk, but you can change 
it to use services like S3, Cloudinary, or other storage systems. Since the file system is based on the Spatie Laravel Media Library, 
you also need to define the name of the collection of images and thumbnails for your Models.

```php filename="config/shopper/media.php"
'storage' => [
    'collection_name' => 'uploads',
    'thumbnail_collection' => 'thumbnail',
    'disk_name' => 'public',
]
```

### Accepts Mime Types

Lists the MIME types allowed for media files. This ensures that only specified file formats can be uploaded. For your need you can add more types.

```php filename="config/shopper/media.php"
'accepts_mime_types' => [
    'image/jpg',
    'image/jpeg',
    'image/png',
]
```

### Media Filesize

Sets the maximum allowed file size for media uploads (in kilobytes). This helps control file sizes to avoid performance issues.

```php filename="config/shopper/media.php"
'max_size' => [
    'thumbnail' => 1024, // Default size for thumbnail image (1MB). 
    'images' => 2048, // Default size for individual collection image for product (2MB)
]
```

### Image Conversions

Configures image conversions to generate resized or optimized versions of uploaded images. For example, 
you can create thumbnails or mobile-friendly images.

```php filename="config/shopper/media.php"
'conversions' => [
    'large' => [
        'width' => 800,
        'height' => 800,
    ],
    'medium' => [
        'width' => 500,
        'height' => 500,
    ],
]
```

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
$product->getUrl('thumb200x200') // or
$product->getUrl(config('shopper.media.storage.thumbnail_collection'))
```

For more information on what's available, see [Defining conversions](https://spatie.be/docs/laravel-medialibrary/v10/converting-images/defining-conversions#content-using-multiple-conversions)

### Images
To get an image with full url on a product, a brand or a collection

```php
$product->getFirstMediaUrl(config('shopper.media.storage.collection_name'))
```

## Advanced Customization

If you need further customization, you can configure a custom disk in `config/filesystems.php` and update the `config/shopper/media.php` configuration.
You can see everything about Storage on the [Laravel documentation](https://laravel.com/docs/11.x/filesystem)
