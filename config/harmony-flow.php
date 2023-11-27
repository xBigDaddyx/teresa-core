<?php



return [
    'guard' => 'ldap',
    'models' => [
        'users' => Domain\Users\Models\User::class,
        'departments' => Xbigdaddyx\HarmonyFlow\Models\Department::class,
        'approvals' => Xbigdaddyx\HarmonyFlow\Models\Approval::class,
        'approval-flows' => Xbigdaddyx\HarmonyFlow\Models\Flow::class,
        'designations' => Xbigdaddyx\HarmonyFlow\Models\Designation::class,
        'companies' => Domain\Users\Models\Company::class,
        'histories' => Xbigdaddyx\HarmonyFlow\Models\History::class,
        'comments' => Domain\Purchases\Models\Comment::class,
        'departments' => Domain\Purchases\Models\Department::class

    ],
    'table_names' => [
        'departments' => 'departments',
        'model_has_departments' => 'model_has_departments',
        'approvals' => 'harmony_approvals',
        'flows' => 'harmony_flows',
        'histories' => 'harmony_approval_histories',
        'designations' => 'harmony_designations',
        'user_has_designation' => 'harmony_user_has_designation',

    ],
    'column_names' => [
        'flow_pivot_key' => 'flow_id',
        'user_morph_key' => 'user_id',
        'department_pivot_key' => 'department_id',


    ]
];
