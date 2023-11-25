<?php

return [
    'debuggers' => [
        'horizon',
    ],

    'authorization' => true,

    'permissions' => [
        'horizon' => 'horizon.view',
        'telescope' => 'telescope.view',
    ],

    'group' => 'Debugger',
];
