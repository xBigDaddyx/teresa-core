<?php

return [
    'guard' => 'ldap',
    'models' => [
        'users' => Domain\Users\Models\User::class,
        'departments' => Domain\Purchases\Models\Department::class,
        'approvals' => Xbigdaddyx\HarmonyFlow\models\Approval::class,
        'approval-flows' => Xbigdaddyx\HarmonyFlow\models\Flow::class,
        'designations' => Xbigdaddyx\HarmonyFlow\models\Designation::class,

    ],
    'table_names' => [
        'approvals' => 'harmony_approvals',
        'flows' => 'harmony_flows',
        'designations' => 'harmony_designations',
        'user_has_designation' => 'harmony_user_has_designation',

    ],
    'column_names' => [
        'flow_pivot_key' => null, //default 'flow_id',
        'user_morph_key' => 'user_id',


    ]
];
