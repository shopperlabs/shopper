# Media
The Shopper Framework supports assigning images to products, brands, collections and categories. It is an additional layer provided by the framework with the help of the [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary)

We recommend organizing your images in a folder offline and keeping a backup in case you need them in the future or mistakenly alter one and wish to revert to the original. You can look at the [documentation](/configuration#update-configurations) for this purpose

## Configuration
For uploading images we are using [FilePond](https://pqina.nl/) and some custom upload component with Livewire.

### Filepond
This component is used to update media at the product level and variants for products.
<div class="screenshot">
  <img src="/img/screenshots/{{version}}/filepond.png" alt="filepond">
  <div class="caption">Filepond upload</div>
</div>

Filepond is used in Shopper Framework only to update images, and for that it takes as parameters the images of your model.

```blade
<x-shopper-forms.filepond
    wire:model="files"
    multiple
    allowImagePreview
    imagePreviewMaxHeight="200"
    allowFileTypeValidation
    allowFileSizeValidation
    maxFileSize="5mb"
    :images="$images"
/>
```

`$images` are a collection of media from the Spatie Media Library.

So you have to set up the file upload in your Livewire component yourself. An example of file upload with filepond is available on Livewire you can learn more on this [link](https://www.laravel-livewire.com/screencasts/s5-integrating-with-filepond).

### Single upload
Single Upload is a reusable Livewire component created for single upload management with Livewire
<div class="screenshot">
  <img src="/img/screenshots/{{version}}/single-upload.png" alt="single upload">
  <div class="caption">Single upload component</div>
</div>

The component used is `Shopper\Http\Livewire\Components\Forms\Uploads\Single` To add it to your page you add this component to your view.

```blade
<livewire:shopper-forms.uploads.single />
```

Everything is done out of the box. You just add this to your Livewire component you can add a listener

```php
namespace App\Http\Livewire;

use Livewire\Component;

class MyComponent extends Component
{
    public ?string $fileUrl = null;

    protected $listeners = [
        'shopper:fileUpdated' => 'onFileUpdate'
    ];

    public function onFileUpdate($file)
    {
        $this->fileUrl = $file;
    }

    public function store()
    {
        $model = YourModel::create([...]);

        if ($this->fileUrl) {
            $model->addMedia($this->fileUrl)
              ->toMediaCollection(config('shopper.core.storage.collection_name'));
        }
    }
}
```

:::warning
To apply this action on your model you have to preapre your model with the Laravel Media Library [configuration](https://spatie.be/docs/laravel-medialibrary/v10/basic-usage/preparing-your-model)
:::

### Multiple upload
The component used is `Shopper\Http\Livewire\Components\Forms\Uploads\Multiple` To add it to your page you add this component to your view.

```blade
<livewire:shopper-forms.uploads.multiple />
```

<div class="screenshot">
  <img src="/img/screenshots/{{version}}/multiple-upload.png" alt="multiple upload">
  <div class="caption">Multiple upload component</div>
</div>

```php
namespace App\Http\Livewire;

use Livewire\Component;

class MyComponent extends Component
{
    public $files = [];

    protected $listeners = [
      'shopper:filesUpdated' => 'onFilesUpdated'
    ];

    public function onFilesUpdate($files)
    {
        $this->files = $files;
    }

    public function store()
    {
        $model = YourModel::create([...]);

        if (collect($this->files)->isNotEmpty()) {
            collect($this->files)->each(
              fn ($file) => $model->addMedia($file)->toMediaCollection(config('shopper.core.storage.collection_name'))
            );
        }
    }
}
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

### Thumbnails
The presence of thumbnails is a very common scenario, which is why Shopper use them.

```php
$product->getUrl('thumb200x200')
```

For more information on what's available, see [Defining conversions](https://spatie.be/docs/laravel-medialibrary/v10/converting-images/defining-conversions#content-using-multiple-conversions)

### Primary
To get an image with full url on a product, a brand or a collection

```php
$product->getFirstMediaUrl(config('shopper.core.storage.disk_name'))
```
