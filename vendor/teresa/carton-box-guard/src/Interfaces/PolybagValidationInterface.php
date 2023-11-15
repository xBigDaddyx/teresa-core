<?php

namespace Teresa\CartonBoxGuard\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface PolybagValidationInterface
{
    public function completeCheck(Model $carton);
}
