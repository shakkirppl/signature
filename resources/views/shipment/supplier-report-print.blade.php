<!DOCTYPE html>
<html>
<head>
    <title>Supplier Final Payment Report</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; }
        .adv-arrears td { color: red; }
        .balance td { font-size: 16px; font-weight: bold; }
        h2, h4 { text-align: center; margin: 0; }
    </style>
</head>
<body onload="window.print()">
<img src="{{ asset('public/image/signature-logo.png') }}" class="logo">
<h2>Supplier Final Payment Report</h2>
<p><strong>Supplier:</strong> {{ $supplier->name ?? 'N/A' }}</p>
@foreach($purchaseConformationDetail as $puDetail)

@endforeach

<table>
    <thead>
        <tr>
            <th>SlNo</th>
            <th>Date</th>
            <th>Description</th>
            <th>Bank / Cash</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
    @php $totalAdvance = 0; @endphp
    @foreach($PurchaseOrder as $po)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $po->date }}</td>
            <td>Adv Amount</td>
            <td>Cash</td>
            <td>{{ $po->advance_amount }}</td>
        </tr>
        @php $totalAdvance += $po->advance_amount; @endphp
    @endforeach
    <tr class="adv-arrears">
        <td colspan="4">Total Advance & Arrears</td>
        <td>{{ number_format($totalAdvance, 2) }}</td>
    </tr>

    <tr><td colspan="5"></td></tr>

    <tr>
        <th>No</th>
        <th>Type</th>
        <th>Tot: Carcass Wt</th>
        <th>Price/KG</th>
        <th>Total Price</th>
    </tr>
    @php $totalWeight = 0; $totalAmount = 0; @endphp
    @foreach($purchaseConformationDetail as $puDetail)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $puDetail->product->product_name ?? 'N/A' }}</td>
            <td>{{ $puDetail->total_weight }}</td>
            <td>{{ $puDetail->rate }}</td>
            <td>{{ number_format($puDetail->total_weight * $puDetail->rate, 2) }}</td>
        </tr>
        @php
            $totalWeight += $puDetail->total_weight;
            $totalAmount += $puDetail->total_weight * $puDetail->rate;
        @endphp
    @endforeach
    <tr class="total-row">
        <td colspan="2">Tot Wt.:</td>
        <td>{{ number_format($totalWeight, 2) }}</td>
        <td>Total Amount</td>
        <td>{{ number_format($totalAmount, 2) }}</td>
    </tr>

    <tr>
        <th>No</th>
        <th>Type</th>
        <th>Meat Weight</th>
        <th>Support Amount</th>
        <th>Amount</th>
    </tr>
    @php $totalTransportAmount = 0; @endphp
    @foreach($purchaseConformationDetail as $puDetail)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $puDetail->product->product_name ?? 'N/A' }}</td>
            <td>{{ $puDetail->total_weight }}</td>
            <td>{{ $puDetail->transportation_amount }}</td>
            <td>{{ number_format($puDetail->total_weight * $puDetail->transportation_amount, 2) }}</td>
        </tr>
        @php $totalTransportAmount += $puDetail->total_weight * $puDetail->transportation_amount; @endphp
    @endforeach

    <tr class="total-row">
        <td colspan="4">Total Transport Amount</td>
        <td>{{ number_format($totalTransportAmount, 2) }}</td>
    </tr>

    <tr class="total-row">
        <td colspan="4">Total Amount for Meat</td>
        <td>{{ number_format($totalAmount, 2) }}</td>
    </tr>

    <tr class="total-row">
        <td colspan="4">Grand Total</td>
        <td>{{ number_format($totalAmount + $totalTransportAmount, 2) }}</td>
    </tr>

    <tr class="adv-arrears">
        <td colspan="4">Total of Adv. & Arrears (minus)</td>
        <td>{{ number_format($totalAdvance, 2) }}</td>
    </tr>

    <tr class="balance">
        <td colspan="4">BALANCE PAYABLE</td>
        <td>{{ number_format(($totalAmount + $totalTransportAmount) - $totalAdvance, 2) }}</td>
    </tr>

    </tbody>
</table>
<script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>
