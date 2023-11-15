<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Domain\Purchases\Models\Product;
use Illuminate\Http\Request;
use Support\Controllers\Controller as ControllersController;

class ProductController extends ControllersController
{
    public function index($record)
    {
        $detail = Product::with('category', 'unit')->find($record);
        $medias = $detail->getMedia('products');
        return view('purchase.pages.product', compact('detail', 'medias'));
    }
}
