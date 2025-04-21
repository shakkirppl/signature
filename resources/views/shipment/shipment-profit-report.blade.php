<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Profit Calculation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-size: 12px;
            }

            .container {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            .btn, .text-end, .bi {
                display: none !important;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 4px;
                font-size: 11px;
            }

            h4 {
                text-align: center;
                margin-bottom: 20px;
                font-size: 16px;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000 !important;
            }
        }

        /* Optional: improve spacing on screen view as well */
        th, td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h4 class="card-title">Shipment Report - {{ $shipment->shipment_no }}</h4>
        <div class="mb-3 text-end no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print Report
            </button>
        </div>
        <table class="table table-bordered" id="myTable">
            <thead>
                @foreach ($productSummary as $product => $summary)
                <tr>
                    <th></th>
                    <th colspan="5">
                        {{ $shipment->shipment_no }} - {{ $summary['total_number'] }} {{ $product }}
                        (avg weight {{ $summary['total_weight'] }} kg)
                    </th>
                </tr>
                @endforeach

                <tr>
                    <th></th>
                    <th></th>
                    <th>Qty</th>
                    <th>Price TZS</th>
                    <th>Amount TZS</th>
                    <th>Amount USD</th>
                </tr>
            </thead>
            <tbody>
    
    <tr>
                <td></td>
                <td>Meat purchase kilo rate</td>
                <td>{{ $totalWeight }}</td>  
                <td>{{ number_format($purchaseSummary->total_item_cost/$totalWeight, 2) }}</td>
                <td>{{ number_format($purchaseSummary->total_item_cost, 2) }}</td>
                <td>
                {{ number_format($purchaseSummary->total_item_cost/$exchangeRateShilling, 2) }}
                </td>
            </tr>
        <!-- <tr>
            <td></td>
            <td>Meat cloth</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr> -->
        @foreach($expenseVouchers as $expense)
<tr>
    <td></td>
    <td>{{ $expense->name }}</td> 
    <td></td> 
    <td>{{ number_format($expense->amount/$totalWeight, 2) }}</td>
    <td>{{ number_format($expense->amount, 2) }}</td>
    <td>{{ number_format($expense->amount / $exchangeRateShilling, 2) }}</td>

</tr>
@endforeach
       
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
    <td></td>
    <td>Offals</td>
    <td>{{ $offalsales->qty ?? 0 }}</td>
    <td>{{ number_format($offalsales->total/$offalsales->qty ?? 0, 2) }}</td>
    <td>{{ number_format(($offalsales->total ?? 0), 2) }}</td>
    <td>{{ number_format(($offalsales->total/$exchangeRateShilling ?? 0), 2) }}
    </td>
</tr>


        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
          
            <td></td>
            <td><strong>Total Shipment Cost </strong></td>
            <td></td>
            <td ></td>
            <td><strong>{{ number_format($netShipmentCostTZS, 2) }} </strong></td>
            <td>{{ number_format($netShipmentCostTZS / $exchangeRateShilling, 2) }}</td>
        </tr>

        <tr>
            
            <td></td>
            <td><strong>Per kg in shilling</strong></td>
            <td></td>
            <td></td>
            <td><strong>{{$netShipmentCostTZS/$totalWeight}}</strong></td>
            <td></td>
        </tr>
       
        <tr>
           
            <td></td>
            <td><strong>Per kg in USD </strong></td>
            <td></td>
            <td></td>
            <td>{{($netShipmentCostTZS/$totalWeight)/$exchangeRateShilling}}</td>
            <td></td>
        </tr>
        <tr>
    <td></td>
    <td><strong> Sales Amount </strong></td>
    <td></td>
    <td>{{ number_format($salesAmount, 2) }}</td>
    <td>{{ number_format($salesAmount * $exchangeRateShilling, 2) }}</td>
    <td></td>
</tr>
        <tr>
    <td></td>
    <td><strong> Selling price in USD</strong></td>
    <td></td>
    <td></td>
    <td>{{ number_format($packagingValue, 2) }}</td>
    <td>{{ number_format(($packagingValue * ($shrinkageValue / 100)), 2) }}</td>
</tr>

        <tr> <td></td>
            <td><strong>Profit 1 kg in USD</strong></td>
           
            <td></td>
            <td></td>
            <td>{{ number_format($netProfitUsd/$totalWeight, 2) }}</td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td><strong>Investor Profit</strong></td>
            <td></td>
             <td>{{ number_format($investorProfit, 2) }}</td>
                   <td></td>

            
            <td></td>
        </tr>
        <tr>
       
           
            <td></td>
            <td><strong>Profit 1 shipment</strong></td>
            <td></td>
            <td></td>
            <td>{{ number_format($netProfitUsd*$exchangeRateShilling, 2, '.', ',') }}</td>
            <td>{{ number_format($netProfitUsd, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td><strong>1 shipment expecting profit 10,000 * 0.97 cent</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td><strong>Monthly expecting shipment 8</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
       

       
    </tbody>
    </table>
    </div>

    <!-- JavaScript logic stays the same -->
   
</body>
</html>