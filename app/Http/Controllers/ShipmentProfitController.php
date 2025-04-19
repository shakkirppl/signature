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
    $packagingValue = $salesPayment ? $salesPayment->packaging : 0;

    $sellingPriceUSD = 6.80;

$shipmentCostTZS = $purchaseSummary->total_item_cost 
    + $expenseVouchers->sum('amount') 
    + $packagingValue 
    + $shrinkageValue;

$offalIncomeTZS = $offalsales ? $offalsales->total : 0;

// Total weight (assuming 8kg average per item)
$totalQty = $purchaseSummary->qty ?? 1;
$totalWeight = $totalQty * 8;

$netShipmentCostTZS = $shipmentCostTZS - $offalIncomeTZS;

// Cost per kg in TZS
$perKgShilling = $totalWeight > 0 ? ($netShipmentCostTZS / $totalWeight) : 0;

// Selling price per kg in TZS
$sellingPriceTZS = $sellingPriceUSD * $rate;

// Shrinkage per kg in TZS
$shrinkagePerKg = $totalWeight > 0 ? ($shrinkageValue / $totalWeight) : 0;

// Profit per kg
$perKgUSD = $totalWeight > 0 && $rate > 0 ? ($netShipmentCostTZS / $totalWeight / $rate) : 0;

// Final profit
$profit1Shipment = $perKgUSD * $totalWeight;
$investorProfit = 0.00;


    
        return view('shipment.shipment-profit-report', compact('shipment','productSummary',
        'purchaseSummary','rate','offalsales','expenseVouchers','shrinkageValue',
        'packagingValue','profit1Shipment','perKgUSD','investorProfit'));
    }



}
