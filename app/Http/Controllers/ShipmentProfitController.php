<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\PurchaseConformation;
use App\Models\UsdToShilling;
use App\Models\OffalSales;
use App\Models\ExpenseVoucher;
use App\Models\SalesPayment;
class ShipmentProfitController extends Controller
{
    public function shipmentprofit($id)

    {
        $shipment = Shipment::with(['weightCalculatorMaster.details.product'])
        ->findOrFail($id);
        $productSummary = [];

        foreach ($shipment->weightCalculatorMaster as $weightCalculator) {
            foreach ($weightCalculator->details as $detail) {
                $productName = $detail->product->product_name; // 
                
                if (!isset($productSummary[$productName])) {
                    $productSummary[$productName] = [
                        'total_number' => 0,
                        'total_weight' => 0,
                    ];
                }
    
               
                $productSummary[$productName]['total_number'] += $detail->total_accepted_qty;
                $productSummary[$productName]['total_weight'] += $detail->weight;
            }
        }
        $purchaseSummary = PurchaseConformation::where('shipment_id', $id)
        ->join('purchase_conformation_detail', 'purchase_conformation_detail.conformation_id', '=', 'purchase_conformation.id')
        ->selectRaw('SUM(purchase_conformation.item_total) as total_item_cost, SUM(purchase_conformation_detail.total_accepted_qty) as qty')
        ->first();

        $exchangeRate = UsdToShilling::latest()->first();
        $rate = $exchangeRate ? $exchangeRate->shilling : 1; // Default to 1 if no rate found
         
        $offalsales = OffalSales::where('shipment_id', $id)
        ->join('offal_sales_detail', 'offal_sales_detail.offal_sales_id', '=', 'offal_sales_master.id')
        ->selectRaw('SUM(offal_sales_detail.total) as total, SUM(offal_sales_detail.qty) as qty')
        ->first();

        $expenseVouchers = ExpenseVoucher::where('shipment_id', $id)
    ->join('account_heads', 'expense_voucher.coa_id', '=', 'account_heads.id')
    ->select('account_heads.name', 'expense_voucher.amount')
    ->get();

    $salesPayment = SalesPayment::where('shipment_id', $id)->first();
    $shrinkageValue = $salesPayment ? $salesPayment->shrinkage : 0; 

    
        return view('shipment.shipment-profit-report', compact('shipment','productSummary','purchaseSummary','rate','offalsales','expenseVouchers','shrinkageValue'));
    }



}
