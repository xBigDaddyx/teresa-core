<?php

// config for Teresa/CartonBoxGuard

use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\Polybag;

return [
    'carton' => [
        'model' => CartonBox::class,
        'table_name' => 'carton_boxes',
    ],
    'polybag' => [
        'model' => Polybag::class,
        'table_name' => 'polybags',
    ],
    'database_connection' => 'teresa_box',
];
