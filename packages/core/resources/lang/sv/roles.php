<?php

declare(strict_types=1);

return [
    'admin' => [
        'display_name' => 'Administratör',
        'description' => 'Sajtsadministratör med tillgång till butikens adminpanel och utvecklarverktyg.',
    ],
    'manager' => [
        'display_name' => 'Butikschef',
        'description' => 'Butikschef med tillgång till katalogen, ordrar, rabatter och kunder. Inställningar och teamhantering är förbehållna administratörer.',
    ],
    'user' => [
        'display_name' => 'Användare',
        'description' => 'Sajtkundroll med tillgång till webbplatsen.',
    ],
];
