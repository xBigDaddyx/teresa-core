<?php

namespace Teresa\CartonBoxGuard\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Teresa\CartonBoxGuard\Services\PolybagValidationService;

class PolybagRepository
{
    protected $validationService;

    protected $polybagModel;

    public function __construct(PolybagValidationService $validationService)
    {
        $this->validationService = $validationService;
        $this->polybagModel = $this->getPolybagModel();
    }
    public function completeCheck(Model $carton)
    {
        $this->validationService->completeCheck($carton);
    }
    public function getPolybagModel()
    {
        return resolve(Config::get('carton-box-guard.polybag.model'));
    }
}
