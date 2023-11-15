<?php

namespace App\Http\Controllers;

use Filament\Facades\Filament;
use Support\Controllers\Controller;
use Teresa\CartonBoxGuard\Facades\CartonBoxFacade;

class CartonBoxCheckController extends Controller
{
    public function index()
    {
        // $box = CartonBoxFacade::validateCarton('1234');
        // $message = CartonBoxFacade::validateSolid('1234');

        return view('accuracy.pages.check-carton');
    }
}
