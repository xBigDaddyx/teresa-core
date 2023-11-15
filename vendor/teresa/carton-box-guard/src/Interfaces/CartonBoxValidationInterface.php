<?php

namespace Teresa\CartonBoxGuard\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CartonBoxValidationInterface
{
    public function validateSolid(Model $cartonBox, string $current_polybag);

    public function validateRatio($cartonBox, $garmentLabel, $packingList, $polybag);
}
