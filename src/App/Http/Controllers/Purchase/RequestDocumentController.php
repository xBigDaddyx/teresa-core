<?php

namespace App\Http\Controllers\Purchase;

use Domain\Purchases\Models\Category;
use Domain\Purchases\Models\Request as ModelsRequest;
use Illuminate\Http\Request;
use Support\Controllers\Controller;

class RequestDocumentController extends Controller
{

    public function index($record)
    {
        $detail = ModelsRequest::with('requestItems', 'requestItems.product', 'requestItems.product.unit')->find($record);

        return view('purchase.reports.request', compact('detail'));
    }
}
