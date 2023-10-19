<?php

namespace App\Http\Controllers;

use Domain\Accuracies\Models\CartonBox;
use Domain\Users\Models\User;
use Filament\Facades\Filament;
use Support\Controllers\Controller;
use Teresa\CartonBoxGuard\Facades\CartonBoxFacade;

class PolybagValidationController extends Controller
{
    public function index($carton)
    {
        // $box = CartonBoxFacade::validateCarton('1234');
        // $message = CartonBoxFacade::validateSolid('1234');

        return view('accuracy.pages.validating-polybag', ['carton' => $carton]);
    }
    public function completed($carton)
    {

        $carton_detail = CartonBox::with('completedBy')->find($carton);
        $user = User::find($carton_detail->completed_by);
        return view('accuracy.pages.completed-carton', ['carton' => $carton_detail, 'user' => $user]);
    }
}
