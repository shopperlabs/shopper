<?php

declare(strict_types=1);

use Shopper\Livewire\Components;
use Shopper\Livewire\Pages;

return [

    'pages' => [],

    'components' => [
        'attributes.browse' => Components\Attributes\Browse::class,
        'attributes.create' => Components\Attributes\Create::class,
        'attributes.edit' => Components\Attributes\Edit::class,
        'attributes.values' => Components\Attributes\Values::class,

        'products.browse' => Components\Products\Browse::class,
        'products.create' => Components\Products\Create::class,
        'products.edit' => Components\Products\Edit::class,
        'products.form.attributes' => Components\Products\Form\Attributes::class,
        'products.form.edit' => Components\Products\Form\Edit::class,
        'products.form.inventory' => Components\Products\Form\Inventory::class,
        'products.form.related-products' => Components\Products\Form\RelatedProducts::class,
        'products.form.seo' => Components\Products\Form\Seo::class,
        'products.form.shipping' => Components\Products\Form\Shipping::class,
        'products.form.variants' => Components\Products\Form\Variants::class,
        'products.variant' => Components\Products\Variant::class,
        'products.variant-stock' => Components\Products\VariantStock::class,
    ],

];
