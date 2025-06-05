<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActionHistory;
use App\Models\AccountHead;
use App\Models\BankMaster;
use App\Models\PaymentVoucher;
use App\Models\Shipment;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\SalesOrder;
use App\Models\Product;

class ActionHistoryController extends Controller
{
   public function report(Request $request)
{
    $query = ActionHistory::with('user');

    // Rest of your filtering code...

    $histories = $query->latest()->get();

    $coaNames = AccountHead::pluck('name', 'id')->toArray();
    $bankNames = BankMaster::pluck('bank_name', 'id')->toArray();
    $shipments = Shipment::pluck('shipment_no', 'id')->toArray();
    $customers = Customer::pluck('customer_name', 'id')->toArray();
    $suppliers = Supplier::pluck('name', 'id')->toArray();
    $salesOrders = SalesOrder::pluck('order_no', 'id')->toArray();
    $products = Product::pluck('product_name', 'id')->toArray();
    $pageNames = ActionHistory::select('page_name')->distinct()->pluck('page_name');

    return view('actionhistory.report', compact(
        'histories', 
        'coaNames', 
        'bankNames',
        'pageNames',
        'shipments',
        'customers',
        'suppliers',
        'salesOrders',
        'products'
,    ));
}
}

