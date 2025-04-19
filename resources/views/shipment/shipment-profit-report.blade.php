<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Profit Calculation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
    <h4 class="card-title">Shipment Report - {{ $shipment->shipment_no }}</h4>
        <table class="table table-bordered" id="myTable">
    <thead>
    
            @foreach ($productSummary as $product => $summary)
    <tr>
        <th></th>
        <th>
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
                <td>{{ collect($productSummary)->sum('total_weight') }}</td>  
                <td>{{ number_format($purchaseSummary->total_item_cost, 2) }}</td>
                <td>{{ number_format($purchaseSummary->qty * $purchaseSummary->total_item_cost, 2) }}</td>
                <td>
                    @if($rate > 0)
                        {{ number_format(($purchaseSummary->qty * $purchaseSummary->total_item_cost) / $rate, 2) }}
                    @else
                        0.00
                    @endif
                </td>
            </tr>
        <tr>
            <td></td>
            <td>Meat cloth</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($expenseVouchers as $expense)
<tr>
    <td></td>
    <td>{{ $expense->name }}</td> 
    <td></td> 
    <td>{{ number_format($expense->amount, 2) }}</td>
    <td>{{ number_format($expense->amount, 2) }}</td>
    <td>{{ number_format($expense->amount / $rate, 2) }}</td>

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
            <td>{{$offalsales->qty}}</td>
            <td>{{ number_format($offalsales->total, 2) }}</td>
            <td>{{ number_format($offalsales->qty * $offalsales->total, 2) }}</td>
            <td>   @if($rate > 0)
                {{ number_format(($offalsales->qty * $offalsales->total) / $rate, 2) }}
            @else
                0.00
            @endif</td>
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
            <td><strong></strong></td>
            <td></td>
        </tr>

        <tr>
            
            <td></td>
            <td><strong>Per kg in shilling</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @php
    // Step 1: Calculate shrinkage amount
    $shrinkageAmount = $packagingValue * ($shrinkageValue / 100);

    // Step 2: Profit per kg in USD
    $profitPerKg = ($packagingValue - $shrinkageAmount) - $perKgUSD;

    // Step 3: Net profit (after investor share)
    $netProfit = $profitPerKg - $investorProfit;
    
@endphp
        <tr>
           
            <td></td>
            <td><strong>Per kg in USD </strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
    <td></td>
    <td><strong> Selling price in USD</strong></td>
    <td></td>
    <td>{{ number_format($packagingValue, 2) }}</td>
    <td>{{ number_format(($packagingValue * ($shrinkageValue / 100)), 2) }}</td>
    <td></td>
</tr>

        <tr> <td></td>
            <td><strong>Profit 1 kg in USD</strong></td>
           
            <td></td>
            <td></td>
            <td>{{ number_format($profitPerKg, 2) }}</td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td><strong>Investor Profit</strong></td>
            <td></td>
             <td>{{ number_format($investorProfit, 2) }}</td>
                   <td>{{ number_format($profitPerKg - $investorProfit,2) }}</td>

            
            <td></td>
        </tr>
        <tr>
        @php
    $offalTotal = $offalsales->total ?? 0;
    $totalQty = $purchaseSummary->qty ?? 1;
    $totalWeight = $totalQty * 8;

    $shipmentCostTZS = $purchaseSummary->total_item_cost
        + $expenseVouchers->sum('amount')
        + $packagingValue
        + $shrinkageValue;

    $netShipmentCostTZS = $shipmentCostTZS - $offalTotal;
    $perKgShilling = $totalWeight > 0 ? $netShipmentCostTZS / $totalWeight : 0;

    $sellingPriceUSD = 6.80;
    $sellingPriceTZS = $sellingPriceUSD * $rate;

    $shrinkagePerKg = $totalWeight > 0 ? $shrinkageValue / $totalWeight : 0;

    $profitPerKg = $sellingPriceTZS - $perKgShilling - $shrinkagePerKg;
    $profitShipment = $netProfit * $totalWeight;

@endphp
           
            <td></td>
            <td><strong>Profit 1 shipment</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ number_format($profitShipment, 2) }}</td>
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
    <script>
document.addEventListener("DOMContentLoaded", function () {
    let rows = document.querySelectorAll("#myTable tbody tr");
    let slNo = 1;
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const labelCell = row.querySelector("td:nth-child(2)");
        if (labelCell) {
            const text = labelCell.textContent.trim();
            if (text === "Offals") {
                break; // stop numbering when Offals is reached
            }
            row.querySelector("td:nth-child(1)").textContent = slNo++;
        }
    }
});
</script>


<!-- Script: Financial Calculation -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    function formatMillions(value) {
        return value >= 1_000_000 ? (value / 1_000_000).toFixed(2) + 'M' : value.toLocaleString(undefined, { minimumFractionDigits: 2 });
    }

    let sumTZS = 0;
    let offalAmount = 0;

    document.querySelectorAll("#myTable tbody tr").forEach(row => {
        let label = row.querySelector("td:nth-child(2)");
        let amountCell = row.querySelector("td:nth-child(5)");
        if (label && amountCell) {
            let labelText = label.textContent.trim();
            let amount = parseFloat(amountCell.textContent.replace(/,/g, '')) || 0;
            if (labelText === "Offals") {
                offalAmount = amount;
            } else {
                sumTZS += amount;
            }
        }
    });

    let exchangeRate = {{ $rate }};
    let totalWeight = {{ collect($productSummary)->sum('total_weight') }};
    let shrinkagePrice = 6.80;

    let totalShipmentCost = sumTZS - offalAmount;
    let sumUSD = exchangeRate > 0 ? (totalShipmentCost / exchangeRate) : 0;
    let perKgShilling = totalWeight > 0 ? (totalShipmentCost / totalWeight) : 0;
    let perKgUSD = totalWeight > 0 && exchangeRate > 0 ? (totalShipmentCost / totalWeight / exchangeRate) : 0;

    let profitPerKgUSD = shrinkagePrice - perKgUSD;
    let investorProfit = 0.30;
    let netProfit = {{ $netProfit ?? 0 }};
    let totalQty = {{ $purchaseSummary->qty ?? 1 }};
    let profitShipment = netProfit * totalQty;

    document.querySelectorAll("#myTable tbody tr").forEach(row => {
        let label = row.querySelector("td:nth-child(2)");
        if (label) {
            let labelText = label.textContent.trim();
            switch (labelText) {
                case "Total Shipment Cost":
                    row.querySelector("td:nth-child(5)").textContent = totalShipmentCost.toLocaleString(undefined, { minimumFractionDigits: 2 });
                    row.querySelector("td:nth-child(6)").textContent = sumUSD.toLocaleString(undefined, { minimumFractionDigits: 2 });
                    break;
                case "Per kg in shilling":
                    row.querySelector("td:nth-child(5)").textContent = perKgShilling.toFixed(2);
                    break;
                case "Per kg in USD":
                    row.querySelector("td:nth-child(5)").textContent = perKgUSD.toFixed(2);
                    break;
                case "Profit 1 kg in USD":
                    row.querySelector("td:nth-child(5)").textContent = profitPerKgUSD.toFixed(2);
                    break;
                case "Investor Profit":
                    row.querySelector("td:nth-child(4)").textContent = investorProfit.toFixed(2);
                    break;
                case "Profit 1 shipment":
                    row.querySelector("td:nth-child(5)").textContent = formatMillions(profitShipment);
                    break;
            }
        }
    });
});
</script>





</body>
</html>
