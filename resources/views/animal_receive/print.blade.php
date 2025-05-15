<!DOCTYPE html>
<html>
<head>
    <title>Animal Receive Note - Print</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: flex-start;
        }
        .signature-box {
            width: 150px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
        }
        @media print {
    .no-print {
        display: none !important;
    }
}
    </style>
</head>
<body onload="window.print()">
   <h3 style="text-align: center;">Animal Receive Note</h3>

<div style="display: flex; justify-content: space-between;">
    <!-- Left Column -->
    <div style="width: 52%;">
        <p><strong>Inspection No:</strong> {{ $inspection->inspection_no }}</p>
        <p><strong>Order No:</strong> {{ $inspection->order_no }}</p>
        <p><strong>Supplier:</strong> {{ $inspection->supplier->name ?? '' }}</p>
    </div>

    <!-- Right Column -->
    <div style="width: 48%;">
        <p><strong>Date:</strong> {{ $inspection->date }}</p>
        <p><strong>Shipment No:</strong> {{ $inspection->shipment->shipment_no ?? '' }}</p>
        <p><strong>Mark:</strong> {{ $inspection->mark }}</p>
    </div>
</div>


    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Received</th>
                <th>Male Accepted</th>
                <th>Female Accepted</th>
                <th>Male Rejected</th>
                <th>Female Rejected</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspection->details as $detail)
            <tr>
                <td>{{ $detail->product->product_name ?? '' }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ $detail->received_qty }}</td>
                <td>{{ $detail->male_accepted_qty }}</td>
                <td>{{ $detail->female_accepted_qty }}</td>
                <td>{{ $detail->male_rejected_qty }}</td>
                <td>{{ $detail->female_rejected_qty }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Supplier Signature</p>
        </div>
          <div class="signature-box">
           
        </div>
         <div class="signature-box">
            <div>   @if($inspection->signature)
    <img src="{{ asset('storage/signatures/' . $inspection->signature) }}" width="200">
@endif</div>
           <p>Inspector Signature</p> 
        </div>
        
                           
    </div>
   <div class="no-print" style="margin-top: 30px; text-align: center;">
    <a href="{{ route('animalReceive.index') }}" style="padding: 8px 16px; font-size: 14px; text-decoration: none; background: #ccc; color: #000; border: 1px solid #999;">
        ← Back 
    </a>
</div>
</body>
</html>
