<?php

declare(strict_types=1);

return [

    'menu' => 'Campañas',
    'single' => 'campaña',
    'title' => 'Gestionar campañas de marketing',
    'description' => 'Agrupa promociones en una campaña con un presupuesto opcional de gasto o de uso.',

    'name' => 'Nombre',
    'name_placeholder' => 'Rebajas de verano 2026',
    'currency' => 'Moneda',
    'budget_type' => 'Tipo de presupuesto',
    'budget_amount' => 'Importe del presupuesto',
    'budget_count' => 'Presupuesto de uso',
    'budget' => 'Presupuesto',
    'promotions' => 'Promociones',
    'no_budget' => 'Sin presupuesto',
    'currency_frozen_helper' => 'La moneda no se puede cambiar una vez que la campaña ha registrado movimientos de presupuesto.',

    'create' => [
        'description' => 'Configura una nueva campaña. El resumen de la derecha se actualiza a medida que rellenas el formulario.',
    ],

    'edit' => [
        'description' => 'Actualiza esta campaña y revisa el consumo de su presupuesto.',
    ],

    'sections' => [
        'general' => 'General',
        'general_description' => 'Nombre, moneda y visibilidad de la campaña.',
        'budget' => 'Presupuesto',
        'budget_description' => 'Limita cuánto puede gastar la campaña o cuántas veces se puede usar.',
        'schedule' => 'Programación',
        'schedule_description' => 'Cuándo empieza y termina la campaña.',
        'advanced' => 'Avanzado',
        'advanced_description' => 'Metadatos personalizados adjuntos a la campaña.',
    ],

    'summary' => [
        'title' => 'Resumen de la campaña',
        'empty' => 'Rellena el formulario para ver el resumen actualizarse en tiempo real.',
        'rows' => [
            'name' => 'Nombre',
            'currency' => 'Moneda',
            'budget' => 'Presupuesto',
            'schedule' => 'Programación',
            'visibility' => 'Visibilidad',
        ],
        'visibility_public' => 'Activa',
        'visibility_hidden' => 'Desactivada',
    ],

    'budget_panel' => [
        'title' => 'Consumo del presupuesto',
        'spend' => 'Gasto',
        'count' => 'Uso',
        'none' => 'Esta campaña no tiene límite de presupuesto.',
    ],

    'promotions_panel' => [
        'title' => 'Promociones asociadas',
        'empty' => 'Aún no hay promociones asociadas a esta campaña.',
    ],

    'save' => '¡Campaña :name guardada correctamente!',

];
