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
    </style>
</head>
<body onload="window.print()">
    <h3>Animal Receive Note</h3>
    <p><strong>Inspection No:</strong> {{ $inspection->inspection_no }}</p>
    <p><strong>Order No:</strong> {{ $inspection->order_no }}</p>
    <p><strong>Supplier:</strong> {{ $inspection->supplier->name ?? '' }}</p>
    <p><strong>Date:</strong> {{ $inspection->date }}</p>
    <p><strong>Shipment No:</strong> {{ $inspection->shipment->shipment_no ?? '' }}</p>

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
</body>
</html>
