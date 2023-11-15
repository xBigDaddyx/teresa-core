<?php

namespace Teresa\CartonBoxGuard\Services;

use Illuminate\Database\Eloquent\Model;
use Teresa\CartonBoxGuard\Interfaces\CartonBoxValidationInterface;
use Teresa\CartonBoxGuard\Models\Polybag;

class CartonBoxValidationService implements CartonBoxValidationInterface
{
    public function validateSolid(Model $cartonBox, string $current_polybag)
    {
        if ($cartonBox->is_completed !== true) {
            if (! empty($cartonBox->polybags->first()->polybag_code)) {
                if ($current_polybag !== $cartonBox->polybags->first()->polybag_code) {
                    return 'Polybag tidak sesuai';
                }
            }

            $value = new Polybag(['polybag_code' => $current_polybag]);

            $cartonBox->polybags()->save($value);

            return 'Polybag '.$current_polybag.' berhasil ditambahkan, lanjutkan'; // Validasi solid berhasil
        }

        return 'carton box completed.';
    }

    public function validateRatio($cartonBox, $garmentLabel, $packingList, $polybag)
    {
        if ($cartonBox->garmentLabel !== $garmentLabel) {
            return 'Garment tidak sesuai';
        }

        // Implementasikan validasi lain sesuai dengan aturan yang Anda sebutkan
    }
}
