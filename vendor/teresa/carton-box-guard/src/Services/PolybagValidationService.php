<?php

namespace Teresa\CartonBoxGuard\Services;

use Illuminate\Database\Eloquent\Model;
use Teresa\CartonBoxGuard\Interfaces\PolybagValidationInterface;
use Teresa\CartonBoxGuard\Models\CartonBox;

class PolybagValidationService implements PolybagValidationInterface
{
    public function completeCheck(Model $carton)
    {

        $maxQuantity = (int)$carton->quantity;

        $polybags = $carton->polybags()->count();

        if ($polybags = $maxQuantity) {

            return true;
        }
        return false;
    }
}
