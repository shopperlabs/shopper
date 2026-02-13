<?php

declare(strict_types=1);

return [

    'menu' => 'Productos',
    'single' => 'producto',
    'title' => 'Gestionar Catálogo',
    'content' => 'Acércate a tu primera venta añadiendo y gestionando productos.',
    'about_pricing' => 'Sobre la visualización de precios',
    'about_pricing_content' => 'Todos los precios están en centavos por defecto. Para guardar 10€ (o 10$) debes ingresar 1000 centavos para que el formato de moneda sea correcto.',

    'amount_price_help_text' => 'Precio de compra, antes de descuentos.',
    'compare_price_help_text' => 'Precio de venta recomendado, para comparación con el precio de compra. Este precio suele ser más alto',
    'cost_per_items_help_text' => 'El precio de fabricación original. Los clientes no lo verán',
    'safety_security_help_text' => 'El stock de seguridad es el stock límite para tus productos que te alerta si el stock del producto pronto se agotará.',
    'quantity_inventory' => 'Cantidad en Inventario',
    'manage_inventories' => 'Gestionar Inventarios',
    'inventory_name' => 'Nombre del inventario',
    'product_can_returned' => 'Este producto puede ser devuelto',
    'product_can_returned_help_text' => 'Los usuarios tienen la opción de devolver este producto si hay un problema o insatisfacción.',
    'product_shipped' => 'Este producto será enviado',
    'product_shipped_help_text' => 'Asegúrate de completar la información concerniente al envío del producto.',
    'general' => 'Información del producto',
    'status' => 'Disponibilidad del producto',
    'featured_help_text' => 'Este producto será marcado como destacado.',
    'visible_help_text' => 'Este producto estará oculto de todos los canales de venta.',
    'availability_description' => 'Especifica una fecha de publicación para que tu producto esté programado en tu tienda.',
    'type' => 'Tipo de producto',
    'product_type' => 'Establecer como tipo de producto por defecto',
    'product_type_helpText' => 'Esta configuración se guardará para los próximos productos que crees.',
    'product_associations' => 'Asociaciones',
    'related_products' => 'Productos relacionados',
    'quantity_available' => 'Cantidad Disponible',
    'current_qty_inventory' => 'Cantidad actual en este inventario',
    'stock_inventory_heading' => 'Stock e Inventario',
    'stock_inventory_description' => 'Configura el inventario y stock para este :item',
    'files_helpText' => 'Añade los archivos que serán descargables con la compra de este producto.',
    'images_helpText' => 'Añade imágenes a tu producto.',
    'variant_images_helpText' => 'Añade imágenes a tu variante.',
    'thumbnail_helpText' => 'Usado para representar tu producto durante el pago, compartir en redes sociales y más.',
    'weight_dimension' => 'Peso y Dimensión',
    'weight_dimension_help_text' => 'Usado para calcular cargos de envío durante el pago y para etiquetar precios durante el procesamiento del pedido.',
    'external_id_description' => 'El identificador original de tu producto del proveedor externo',
    'allow_backorder' => 'Permitir pedido en espera (backorder)',

    'modals' => [
        'title' => 'Eliminar este :item',
        'message' => '¿Estás seguro de que deseas eliminar este producto? Toda la información asociada con este producto será eliminada.',

        'variants' => [
            'title' => 'Gestión de stock para esta variante',
            'select' => 'Seleccionar inventario',
            'add' => 'Añadir nueva variante',
            'options' => [
                'title' => 'Atributos de la variante',
                'description' => 'Selecciona opciones de atributos para esta variante.',
            ],
        ],
    ],

    'variants' => [
        'menu' => 'Variantes',
        'single' => 'variante',
        'title' => 'Variaciones de productos',
        'description' => 'Todas las variaciones de tu producto. Las variaciones pueden tener cada una su stock y precio.',
        'add' => 'Añadir variante',
        'generate' => 'Generar variantes',
        'generate_description' => 'Tus productos se generan de acuerdo a los atributos que has seleccionado',
        'variant_title' => 'Variantes ~ :name',
        'empty' => 'No se encontró variante',
        'search_label' => 'Buscar variante',
        'search_placeholder' => 'Buscar variante de producto',
        'variant_information' => 'Información de la variante',
    ],

    'reviews' => [
        'single' => 'reseña',
        'title' => 'Reseñas de clientes',
        'description' => 'Aquí es donde verás las reseñas de tus clientes y las calificaciones dadas a tus productos.',
        'view' => 'Reseñas para :product',
        'published' => 'Publicado',
        'pending' => 'Pendiente',
        'approved' => 'Reseña aprobada',
        'is_recommended' => 'Reseña recomendada',
        'approved_status' => 'Estado de aprobación',
        'approved_message' => '¡Estado de aprobación de la reseña actualizado!',

        'subtitle' => 'Reseña para este producto.',
        'reviewer' => 'Reseñador',
        'review' => 'Reseña',
        'review_content' => 'Contenido',
        'status' => 'Estado',
        'rating' => 'Calificación',
        'star' => 'estrella',
        'stars' => 'estrellas',

        'modal' => [
            'title' => 'Eliminar Reseña',
            'description' => '¿Estás seguro de que deseas eliminar esta reseña? Esta reseña no se podrá recuperar más.',
            'success_message' => '¡Reseña eliminada exitosamente!',
        ],
    ],

    'attributes' => [
        'title' => 'Atributos del producto',
        'description' => 'Todos los atributos asociados con este producto.',
        'choose' => 'Elegir atributos',
        'empty_title' => 'Sin atributos habilitados',
        'empty_values' => 'Los atributos asociados con este producto se listan aquí.',

        'session' => [
            'delete' => 'Atributo eliminado',
            'delete_message' => '¡Has eliminado exitosamente este atributo del producto!',
            'delete_value' => 'Valor de atributo eliminado',
            'delete_value_message' => '¡Has eliminado exitosamente el valor de este atributo!',
            'added' => 'Atributo Añadido',
            'added_message' => '¡Has añadido exitosamente atributos a este producto!',
        ],
    ],

    'inventory' => [
        'title' => 'Atributos de inventario',
        'description' => 'Campos relacionados con la gestión de stock en tu tienda.',
        'stock_title' => 'Gestión de stock',
        'stock_description' => 'Gestión de stock en tus diferentes inventarios.',
        'empty' => 'Sin ajustes realizados al inventario.',
        'movement' => 'Movimiento de cantidad',
        'initial' => 'Inventario inicial',
        'add' => 'Añadido manualmente',
        'remove' => 'Eliminado manualmente',
    ],

    'shipping' => [
        'description' => 'Información del producto sobre devolución o definir si el producto puede ser enviado al cliente.',
        'package_dimension' => 'Dimensión del paquete',
        'package_dimension_description' => 'Cobrar costos de envío adicionales basados en las dimensiones del paquete cubiertas aquí.',
    ],

    'related' => [
        'title' => 'Productos similares',
        'description' => 'Todos los productos que pueden identificarse como similares o complementarios a tu producto.',
        'empty' => 'No se encontraron productos similares',
        'add_content' => 'Comienza añadiendo un producto relacionado a tu producto.',

        'modal' => [
            'title' => 'Añadir productos similares',
            'search' => 'Buscar producto',
            'search_placeholder' => 'Buscar producto por nombre',
            'action' => 'Añadir productos seleccionados',
            'success_message' => 'Producto(s) seleccionado(s) añadido(s)',
            'no_results' => 'No se encontraron productos',
        ],
    ],

    'notifications' => [
        'files_update' => '¡Archivos del producto actualizados!',
        'media_update' => '¡Multimedia del producto actualizada!',
        'replicated' => '¡Producto replicado!',
        'stock_update' => '¡Stock del producto actualizado exitosamente!',
        'seo_update' => '¡SEO del producto actualizado exitosamente!',
        'shipping_update' => '¡Envío del producto actualizado exitosamente!',
        'variation_generate' => 'Variantes del producto guardadas exitosamente',
        'variation_create' => '¡Variante del producto añadida exitosamente!',
        'variation_delete' => '¡La variante se ha eliminado exitosamente!',
        'variation_update' => '¡Variante actualizada exitosamente!',
        'related_added' => '¡El producto se ha añadido exitosamente a los productos relacionados!',
        'remove_related' => '¡El producto se ha eliminado exitosamente de los productos relacionados!',
        'manage_pricing' => '¡El precio de tu producto ha sido actualizado!',
        'variant_already_exists' => '¡Esta variante ya existe!',
    ],

    'pricing' => [
        'title' => 'Precio del producto',
        'description' => 'Los diferentes precios asociados con tu producto. Esto depende de las monedas que tengas en tu tienda.',
        'add' => 'Añadir nuevo precio',
        'empty' => 'Sin precio de producto añadido',
    ],

];
