<?php

declare(strict_types=1);

return [

    'menu' => 'Reseñas',
    'single' => 'reseña',
    'title' => 'Reseñas de clientes',
    'description' => 'Descubre lo que tus clientes dicen sobre tus productos.',

    'stats' => [
        'average' => 'Valoración media',
        'based_on' => 'Basado en :count reseñas',
        'total' => 'Reseñas totales',
        'last_30_days' => 'en los últimos 30 días',
        'no_recent' => 'No hay reseñas nuevas en los últimos 30 días',
        'no_data' => 'Aún no hay reseñas',
        'five_star' => 'Reseñas 5 estrellas',
        'excellent' => 'Excelentes valoraciones',
        'pending' => 'Pendientes de moderación',
        'pending_description' => 'Requiere tu atención',
        'pending_empty' => 'Bandeja vacía',
    ],

    'breakdown' => [
        'title' => 'Distribución de valoraciones',
        'description' => 'Cómo te valoran tus clientes',
    ],

    'recommended' => [
        'title' => 'Recomendación',
        'description' => ':percent% de clientes te recomiendan',
        'empty' => 'Aún no hay recomendaciones',
    ],

    'tabs' => [
        'all' => 'Todas',
        'pending' => 'Pendientes',
        'approved' => 'Aprobadas',
    ],

    'actions' => [
        'approve' => 'Aprobar',
        'reject' => 'Rechazar',
        'mark_as_spam' => 'Marcar como spam',
        'approved_message' => 'Reseña aprobada.',
        'rejected_message' => 'Reseña rechazada.',
        'spam_message' => 'Reseña marcada como spam.',
        'spam_confirmation' => 'Marcar esta reseña como spam la ocultará a tus clientes. Puedes revertirlo desde la cola de moderación.',
    ],

];
