<?php

declare(strict_types=1);

return [

    'menu' => 'Escritorio',
    'welcome_message' => 'Bienvenido a Shopper',
    'welcome_description' => 'Esto es lo que necesitas para poner en marcha tu tienda.',

    'cards' => [
        'doc_title' => 'Documentación',
    ],

    'guide' => [
        'title' => 'Guía de configuración',
        'description' => 'Completa estos pasos para empezar a vender.',
        'progress' => 'de :total completados',
        'dismiss' => 'Ocultar',
        'footer_hint' => 'Siempre puedes acceder a estos ajustes más tarde.',

        'steps' => [
            'add_product' => [
                'title' => 'Añade tu primer producto',
                'description' => 'Añade productos con precios, imágenes y variantes para empezar a construir tu catálogo.',
                'action' => 'Añadir un producto',
            ],
            'create_collection' => [
                'title' => 'Crear una colección',
                'description' => 'Organiza tus productos en colecciones para facilitar la navegación de tus clientes.',
                'action' => 'Crear una colección',
            ],
            'setup_zones' => [
                'title' => 'Configurar zonas de envío',
                'description' => 'Configura tus zonas de envío para definir dónde entregas y a qué costo.',
                'action' => 'Configurar envíos',
            ],
            'setup_payments' => [
                'title' => 'Configurar métodos de pago',
                'description' => 'Añade métodos de pago para que tus clientes puedan pagar sus pedidos.',
                'action' => 'Configurar pagos',
            ],
            'setup_taxes' => [
                'title' => 'Configurar impuestos',
                'description' => 'Configura zonas y tasas de impuestos para calcular automáticamente los impuestos en los pedidos.',
                'action' => 'Configurar impuestos',
            ],
        ],
    ],

    'stats' => [
        'revenue' => 'Ingresos totales',
        'products' => 'Total productos',
        'orders' => 'Total pedidos',
        'customers' => 'Total clientes',
        'vs_last_month' => 'vs mes anterior',
        'view_more' => 'Ver más',
    ],

    'chart' => [
        'heading' => 'Rendimiento',
        'series_label' => 'Ingresos',
    ],

    'recent_orders' => [
        'heading' => 'Pedidos recientes',
        'view_all' => 'Ver todos',
        'empty' => 'Aún no hay pedidos.',
    ],

    'top_products' => [
        'heading' => 'Productos más vendidos',
        'view_all' => 'Ver todos',
        'product' => 'Producto',
        'sales' => 'Ventas',
        'reviews' => 'Reseñas',
        'empty' => 'Aún no hay ventas.',
    ],

    'addons' => [
        'title' => 'Amplía tu tienda',
        'badge' => 'Add-on',
        'learn_more' => 'Más información',
        'configure' => 'Configurar transportistas',

        'stripe' => [
            'title' => 'Stripe',
            'description' => 'Acepta tarjetas de crédito, Apple Pay y Google Pay con Stripe.',
        ],
        'carriers' => [
            'title' => 'Transportistas',
            'description' => 'Conecta UPS, FedEx, USPS y más para tarifas de envío en tiempo real.',
        ],
    ],

];
