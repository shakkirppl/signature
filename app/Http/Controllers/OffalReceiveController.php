<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Product;
use App\Models\Category;

class OffalReceiveController extends Controller
{
    public function create()
    {
       
        $offalCategory = Category::whereRaw('LOWER(name) = ?', ['offal'])->first();

        
        $products = $offalCategory ? Product::where('category_id', $offalCategory->id)->get() : [];
    
        $shipments = Shipment::where('shipment_status', 0)->get();
    
        return view('offal-receive.create', compact('shipments', 'products'));
    }

}
