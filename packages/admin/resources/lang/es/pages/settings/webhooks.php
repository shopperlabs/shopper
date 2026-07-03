<?php

declare(strict_types=1);

return [
    'title' => 'Webhooks',
    'add_webhook' => 'Añadir webhook',
    'no_webhook' => 'Aún no hay suscripciones de webhook. Añade una para notificar a un sistema externo cuando ocurran eventos en tu tienda.',
    'events' => 'Eventos',
    'deliveries' => 'Entregas',
    'no_delivery' => 'Aún no hay entregas para este webhook.',
    'attempt' => 'Intento :number',
    'redeliver' => 'Reenviar',
    'redelivered' => 'La entrega se ha vuelto a poner en cola.',
    'redeliver_refused' => 'El recurso de este evento ya no existe, el payload no puede reenviarse.',
    'created' => 'Webhook creado',
    'updated' => 'Webhook actualizado',
    'unsafe_url' => 'La url del webhook debe usar https y resolver a una dirección pública.',
    'regenerate_secret' => 'Regenerar el secreto',
    'regenerate_secret_warning' => 'El secreto actual deja de funcionar inmediatamente. Actualiza tu endpoint receptor con el nuevo.',
    'secret_regenerated' => 'Secreto regenerado',
    'secret_reveal' => 'Secreto de firma (cópialo ahora, no se mostrará de nuevo): :secret',
    'cap_reached' => 'Se ha alcanzado el número máximo de suscripciones de webhook.',
];
