<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Administrador inicial
    |--------------------------------------------------------------------------
    |
    | Credenciales del unico usuario del panel. En local se usan valores por
    | defecto; en produccion ADMIN_PASSWORD es obligatorio y el seeder falla
    | si no esta definido.
    |
    */

    'name' => env('ADMIN_NAME', 'Administrador'),

    'email' => env('ADMIN_EMAIL', 'admin@superoptica.test'),

    'password' => env('ADMIN_PASSWORD', 'password'),

];
