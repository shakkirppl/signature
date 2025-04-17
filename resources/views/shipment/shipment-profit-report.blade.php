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
            <td>   @if($rate > 0)
                {{ number_format(($purchaseSummary->qty * $purchaseSummary->total_item_cost) / $rate, 2) }}
            @else
                0.00
            @endif</td>
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
            <td>Total Shipment Cost</td>
            <td></td>
            <td ></td>
            <td><strong></strong></td>
            <td></td>
        </tr>

        <tr>
            
            <td></td>
            <td>Per kg in shilling</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
           
            <td></td>
            <td>Per kg in USD</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            
            <td></td>
            <td>Selling price in USD 6.8 (shrinkage 2%)</td>
            <td> </td>
            <td>{{ number_format($shrinkageValue, 2) }}</td>
            <td></td>
            <td></td>
        </tr>

        <tr> <td></td>
            <td>Profit 1 kg in USD</td>
           
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td>Investor Profit</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
           
            <td></td>
            <td>Profit 1 shipment</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
            <td>1 shipment expecting profit 10,000 * 0.97 cent</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Monthly expecting shipment 8</td>
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
            let rows = document.querySelectorAll("#myTable tbody tr td:first-child");
            rows.forEach((cell, index) => {
                if (index < 12) {
                    cell.textContent = index + 1;
                }
            });
        });
    </script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let sumTZS = 0;

    // Sum up the values in "Amount TZS" column (5th column)
    document.querySelectorAll("#myTable tbody tr td:nth-child(5)").forEach(cell => {
        let value = parseFloat(cell.textContent.replace(/,/g, '')) || 0;
        sumTZS += value;
    });

    let exchangeRate = {{ $rate }};
    let totalQty = {{ $purchaseSummary->qty }}; // Get quantity from Laravel

    // Convert the sum to USD
    let sumUSD = exchangeRate > 0 ? (sumTZS / exchangeRate) : 0;

    // Calculate Per kg in Shilling
    let perKgShilling = totalQty > 0 ? (sumTZS / totalQty) : 0;

    document.querySelectorAll("#myTable tbody tr").forEach(row => {
        let firstColumn = row.querySelector("td:nth-child(2)");

        if (firstColumn) {
            let text = firstColumn.textContent.trim();

            if (text === "Total Shipment Cost") {
                let amountTZSCell = row.querySelector("td:nth-child(5)"); 
                let amountUSDCell = row.querySelector("td:nth-child(6)");

                if (amountTZSCell) {
                    amountTZSCell.textContent = sumTZS.toLocaleString(); 
                }
                if (amountUSDCell) {
                    amountUSDCell.textContent = sumUSD.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }); 
                }
            }

            if (text === "Per kg in shilling") {
                let perKgShillingCell = row.querySelector("td:nth-child(5)");
                if (perKgShillingCell) {
                    perKgShillingCell.textContent = perKgShilling.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    });
});
</script>



</body>
</html>
