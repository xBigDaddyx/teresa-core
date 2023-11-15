<?php

namespace Teresa\CartonBoxGuard\Repositories;

use App\Models\CartonBox;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Teresa\CartonBoxGuard\Interfaces\CartonBoxValidationInterface;

class CartonBoxRepository
{
    protected $validationService;

    protected $cartonModel;

    public function __construct(CartonBoxValidationInterface $validationService)
    {
        $this->validationService = $validationService;
        $this->cartonModel = $this->getCartonModel();
    }

    public function validateCarton(string $box_code, string $po = null, string $carton_number = null)
    {

        if ($carton = $this->cartonModel->where('box_code', $box_code)->count() > 1) {
            if ($po !== null) {
                if ($carton_number !== null) {
                    $carton = $this->cartonModel->where('box_code', $box_code)->where('carton_number', $carton_number)->whereHas('packingList', function (Builder $query) use ($po) {
                        $query->where('po', $po);
                    })->first();

                    return $this->cartonModel = $carton;
                }
                $carton = $this->cartonModel->where('box_code', $box_code)->whereHas('packingList', function (Builder $query) use ($po) {
                    $query->where('po', $po);
                })->first();

                return $this->cartonModel = $carton;
            }

            return 'multiple';
        } else {

            return $this->cartonModel = $this->cartonModel->where('box_code', $box_code)->first();
        }
    }

    public function validatePolybag(Model $carton, string $current_polybag, string $closing_polybag = null, bool $polybag_completed = false)
    {

        if ($carton->type === 'SOLID') {
            return $this->validateSolid($carton, $current_polybag);
        }
        if ($carton->type === 'RATIO' || $carton->type === 'MIX') {
            $carton_attribute = $carton->cartonBoxAttributes;
            if ($carton->type === 'MIX') {
                return $this->validateMix($carton, $current_polybag, $closing_polybag, $carton_attribute, $polybag_completed);
            }
            return $this->validateRatio($carton, $current_polybag, $closing_polybag, $carton_attribute, $polybag_completed);
        }
        // if ($carton->type === 'MIX') {
        //     $carton_attribute = $carton->cartonBoxAttributes;
        //     return $this->validateMix($carton, $current_polybag, $carton_attribute, $polybag_completed);
        // }

        return 'Selain SOLID & RATIO';
    }
    // public function validateMix($cartonBox, $polybag, $attribute, $polybag_completed)
    // {
    //     return $this->validationService->validateMix($cartonBox, $polybag, $attribute, $polybag_completed);
    // }
    public function validateSolid($carton, $current_polybag)
    {

        return $this->validationService->validateSolid($carton, $current_polybag);
    }
    public function validateMix($cartonBox, $tag, $polybag, $attribute, $polybag_completed)
    {
        return $this->validationService->validateMix($cartonBox, $tag, $polybag, $attribute, $polybag_completed);
    }
    public function validateRatio($cartonBox, $tag, $polybag, $attribute, $polybag_completed)
    {
        return $this->validationService->validateRatio($cartonBox, $tag, $polybag, $attribute, $polybag_completed);
    }

    public function getMaxPolybagQuantity($carton)
    {
        return $carton->quantity;
    }
    public function getCarton()
    {
        return $this->cartonModel;
    }
    public function getCartonModel()
    {
        return resolve(Config::get('carton-box-guard.carton.model'));
    }
    // Implementasikan metode lain yang diperlukan untuk mengelola model CartonBox
}
