<?php

namespace App\Http\Controllers;

use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\Polybag;
use Domain\Users\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Support\Controllers\Controller;
use Teresa\CartonBoxGuard\Facades\CartonBoxFacade;

class PolybagValidationController extends Controller
{
    public function index(Request $request, $carton)
    {


        return view('accuracy.pages.validating-polybag', ['carton' => $carton]);
    }
    public function completed($carton)
    {

        $carton_detail = CartonBox::with('completedBy')->find($carton);
        $user = User::find($carton_detail->completed_by);
        return view('accuracy.pages.completed-carton', ['carton' => $carton_detail, 'user' => $user]);
    }
}
