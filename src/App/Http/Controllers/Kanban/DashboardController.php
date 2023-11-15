<?php

namespace App\Http\Controllers\Kanban;

use Domain\Kanban\Models\Wise;
use Illuminate\Http\Request;
use Support\Controllers\Controller;

class DashboardController extends Controller
{
    public function index($company)
    {

        return view('kanban.pages.dashboard', ['company' => $company]);
    }
}
