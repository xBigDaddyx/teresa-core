<?php

namespace Teresa\CartonBoxGuard\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CartonBoxValidationInterface
{
    public function validateSolid(Model $cartonBox, string $current_polybag);

    // public function validateMix(Model $cartonBox,  string $polybag, Collection $attribute, bool $polybag_completed);
    public function validateMix(Model $cartonBox, string $tag, string $polybag, Collection $attribute, bool $polybag_completed);
    public function validateRatio(Model $cartonBox, string $tag, string $polybag, Collection $attribute, bool $polybag_completed);
}
