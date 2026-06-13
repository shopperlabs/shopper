<?php

declare(strict_types=1);

return [

    'discount_zone_frozen' => 'No se puede cambiar la zona de un descuento de importe fijo que ya se ha utilizado. La zona permanece fija para mantener la coherencia de la moneda.',

    'discount_terms_frozen' => 'No se puede cambiar el código, el tipo o el valor de un descuento que ya se ha utilizado. Estos términos permanecen fijos para mantener la exactitud de los usos anteriores.',

    'campaign_budget_exceeded' => 'La campaña «:name» ha alcanzado su presupuesto y ya no se puede aplicar.',

    'discount_value' => [
        'not_positive' => 'El valor del descuento debe ser mayor que cero.',
        'percentage_out_of_range' => 'Un descuento porcentual no puede superar el 100 %.',
    ],

];
