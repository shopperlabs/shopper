<?php

declare(strict_types=1);

use Shopper\Livewire;
use Shopper\Livewire\Components;

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Pages
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'product-index' => Livewire\Pages\Product\Index::class,
        'product-create' => Livewire\Pages\Product\Create::class,
        'product-edit' => Livewire\Pages\Product\Edit::class,
        'variant-edit' => Livewire\Pages\Product\Variant::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    */

    'components' => [
        'attributes.browse' => Components\Attributes\Browse::class,
        'attributes.create' => Components\Attributes\Create::class,
        'attributes.edit' => Components\Attributes\Edit::class,
        'attributes.values' => Components\Attributes\Values::class,

        'products.form.attributes' => Components\Products\Form\Attributes::class,
        'products.form.edit' => Components\Products\Form\Edit::class,
        'products.form.media' => Components\Products\Form\Media::class,
        'products.form.inventory' => Components\Products\Form\Inventory::class,
        'products.form.related-products' => Components\Products\Form\RelatedProducts::class,
        'products.form.seo' => Components\Products\Form\Seo::class,
        'products.form.shipping' => Components\Products\Form\Shipping::class,
        'products.form.variants' => Components\Products\Form\Variants::class,
        'products.variant-stock' => Components\Products\VariantStock::class,

        'modals.create-value' => Livewire\Modals\CreateValue::class,
        'modals.products-lists' => Livewire\Modals\ProductsLists::class,
        'modals.related-products-list' => Livewire\Modals\RelatedProductsList::class,
        'modals.update-value' => Livewire\Modals\UpdateValue::class,

        'slide-overs.add-variant' => Livewire\SlideOvers\AddVariantForm::class,
    ],

];
