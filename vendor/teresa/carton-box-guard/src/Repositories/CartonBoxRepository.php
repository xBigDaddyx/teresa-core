<?php

namespace Teresa\CartonBoxGuard\Repositories;

use App\Models\CartonBox;
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

    public function validateCarton(string $box_code)
    {
        return $this->cartonModel = $this->cartonModel->where('box_code', $box_code)->first();
    }

    public function validatePolybag($current_polybag)
    {
        if ($this->cartonModel->type === 'SOLID') {
            return $this->validateSolid($current_polybag);
        }

        return 'Selain SOLID';
    }

    public function validateSolid($current_polybag)
    {

        return $this->validationService->validateSolid($this->cartonModel, $current_polybag);
    }

    public function validateRatio($cartonBox, $garmentLabel, $packingList, $polybag)
    {
        return $this->validationService->validateRatio($cartonBox, $garmentLabel, $packingList, $polybag);
    }

    public function getMaxPolybagQuantity()
    {
        return $this->cartonModel->quantity;
    }

    public function getCartonModel()
    {
        return resolve(Config::get('carton-box-guard.carton.model'));
    }
    // Implementasikan metode lain yang diperlukan untuk mengelola model CartonBox
}
