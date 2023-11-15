<?php

namespace Domain\Accuracies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionFinal extends Model
{
    protected $connection = 'teresa_box';

    protected $guarded = [];

    protected $casts = [
        'is_finish' => 'boolean',
    ];
}
