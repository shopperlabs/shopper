<?php

declare(strict_types=1);

return [
    'budget' => [
        'none' => 'No budget',
        'none_description' => 'The campaign runs without a shared spending or usage cap.',
        'spend' => 'Spend cap',
        'spend_description' => 'Stop honoring codes once the total discounted amount reaches the cap.',
        'count' => 'Usage cap',
        'count_description' => 'Stop honoring codes once the total number of redemptions reaches the cap.',
        'spend_and_count' => 'Spend and usage cap',
        'spend_and_count_description' => 'Whichever cap is reached first stops the campaign.',
    ],
];
