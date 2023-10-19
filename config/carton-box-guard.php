<?php

// config for Teresa/CartonBoxGuard

use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\CartonBoxAttribute;
use Domain\Accuracies\Models\Polybag;
use Domain\Accuracies\Models\Tag;
use Domain\Users\Models\Company;

return [
    'carton' => [
        'model' => CartonBox::class,
        'table_name' => 'carton_boxes',
    ],
    'company' => [
        'model' => Company::class,
        'table_name' => 'companies',
    ],
    'polybag' => [
        'model' => Polybag::class,
        'table_name' => 'polybags',
    ],
    'carton-attribute' => [
        'model' => CartonBoxAttribute::class,
        'table_name' => 'carton_box_attributes',
    ],
    'tag' => [
        'model' => Tag::class,
        'table_name' => 'tags',
    ],
    'database_connection' => 'teresa_box',
];
