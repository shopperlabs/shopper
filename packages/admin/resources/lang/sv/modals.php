<?php

declare(strict_types=1);

return [

    'permissions' => [
        'new' => 'Ny behörighet',
        'new_description' => 'Lägg till en ny behörighet och tilldela direkt till denna roll',
        'labels' => [
            'name' => 'Behörighetsnamn (med gemener)',
        ],
    ],

    'roles' => [
        'new' => 'Lägg till ny roll',
        'new_description' => 'Lägg till en ny roll och tilldela behörigheter för administratörer.',
        'labels' => [
            'name' => 'Namn (med gemener)',
        ],
        'confirm_delete_msg' => 'Är du säker på att du vill ta bort den här rollen? Alla användare som hade denna roll kommer inte längre att kunna utföra de åtgärder som ges av denna roll',
    ],

    'attributes' => [
        'new_value' => 'Lägg till nytt värde för :attribute',
        'key_description' => 'Nyckeln kommer att användas för värdena i lagringen för formulären (alternativ, radio, etc.). Måste vara i slug-format',
        'update_value' => 'Uppdatera värde för :name',
    ],

    'inventories' => [
        'confirm_delete_msg' => 'Är du säker på att du vill radera detta lager? All denna data kommer att tas bort. Denna åtgärd kan inte ångras',
    ],

];
