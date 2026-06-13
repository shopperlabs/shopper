<?php

declare(strict_types=1);

return [

    'discount_zone_frozen' => 'Cannot change the zone of a fixed-amount discount that has already been used. The zone stays frozen to keep the currency consistent.',

    'discount_value' => [
        'not_positive' => 'The discount value must be greater than zero.',
        'percentage_out_of_range' => 'A percentage discount cannot exceed 100%.',
    ],

];
