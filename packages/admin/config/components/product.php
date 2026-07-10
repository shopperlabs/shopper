<?php

declare(strict_types=1);

use Shopper\Livewire;
use Shopper\Livewire\Components;
use Shopper\Navigation\Product\Sections;

return [

    /*
    |--------------------------------------------------------------------------
    | Product Edit Sections
    |--------------------------------------------------------------------------
    |
    | Register the sections that appear in the product edit sidebar.
    | Each section class must implement \Shopper\Contracts\ProductSection.
    |
    | To create a custom section, create a class extending
    | \Shopper\Navigation\Product\AbstractProductSection and add it here with true to enable
    | or false to disable.
    |
    */

    'sections' => [
        Sections\OverviewSection::class => true,
        Sections\MediaSection::class => true,
        Sections\AttributesSection::class => true,
        Sections\VariantsSection::class => true,
        Sections\InventorySection::class => true,
        Sections\PricingSection::class => true,
        Sections\ShippingSection::class => true,
        Sections\FilesSection::class => true,
        Sections\SeoSection::class => true,
        Sections\RelatedProductsSection::class => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'product-index' => Livewire\Pages\Product\Index::class,
        'product-overview' => Livewire\Pages\Product\Overview::class,
        'product-media' => Livewire\Pages\Product\Media::class,
        'product-attributes' => Livewire\Pages\Product\Attributes::class,
        'product-variants' => Livewire\Pages\Product\Variants::class,
        'product-inventory' => Livewire\Pages\Product\Inventory::class,
        'product-pricing' => Livewire\Pages\Product\Pricing::class,
        'product-shipping' => Livewire\Pages\Product\Shipping::class,
        'product-files' => Livewire\Pages\Product\Files::class,
        'product-seo' => Livewire\Pages\Product\Seo::class,
        'product-related' => Livewire\Pages\Product\Related::class,
        'variant-edit' => Livewire\Pages\Product\Variant::class,
        'attribute-index' => Livewire\Pages\Attribute\Browse::class,
        'supplier-index' => Livewire\Pages\Supplier\Index::class,
        'tag-index' => Livewire\Pages\Tag\Index::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'products.delete-action' => Components\Products\DeleteAction::class,
        'products.import-progress' => Components\Products\ImportProgress::class,
        'products.variant-stock' => Components\Products\VariantStock::class,
        'products.type-configuration' => Components\Products\ProductTypeConfiguration::class,
        'products.pricing' => Components\Products\Pricing::class,

        'slide-overs.add-product' => Livewire\SlideOvers\AddProduct::class,
        'slide-overs.import-csv' => Livewire\SlideOvers\ImportCsv::class,
        'slide-overs.add-variant' => Livewire\SlideOvers\AddVariant::class,
        'slide-overs.update-variant' => Livewire\SlideOvers\UpdateVariant::class,
        'slide-overs.generate-variants' => Livewire\SlideOvers\GenerateVariants::class,
        'slide-overs.attribute-form' => Livewire\SlideOvers\AttributeForm::class,
        'slide-overs.choose-product-attributes' => Livewire\SlideOvers\ChooseProductAttributes::class,
        'slide-overs.attribute-values' => Livewire\SlideOvers\AttributeValues::class,
        'slide-overs.manage-pricing' => Livewire\SlideOvers\ManagePricing::class,
        'slide-overs.supplier-form' => Livewire\SlideOvers\SupplierForm::class,
    ],

];
