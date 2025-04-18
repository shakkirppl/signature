@extends('layouts.layout')

@section('content')
<style>
    #report-table, 
    #report-table th, 
    #report-table td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    #report-table th {
        background-color: lightgray; /* Light background for headers */
        text-align: center;
        font-weight: bold;
    }

    #report-table th, 
    #report-table td {
        padding: 8px;
        text-align: center;
    }

    #report-table {
        width: 100%;
        border-spacing: 0;
    }

    .total-row td, 
    .adv-arrears td, 
    .balance td {
        font-weight: bold;
    }

    .adv-arrears td {
        color: red;
    }

    .balance td {
        font-size: 16px;
    }

    @media print {
    .btn, form, select {
        display: none !important;
    }

    body {
        margin: 0;
    }

    #report-table {
        margin-top: 20px;
    }
}
</style>


<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Supplier Final Payment Report</h4>

                    <form method="GET" action="{{ route('shipment-suppllier-final-payment-report-detail') }}">
                    
                        
                        @csrf
                        <div class="col-md-12 text-right">
    @if(request('supplier_id'))
    <a 
        href="{{ route('supplier-final-payment-print', ['supplier_id' => request('supplier_id'), 'shipment_id' => $shipments->id]) }}" 
        target="_blank" 
        class="btn btn-success">
        Print Report
    </a>
    @endif
</div>

                        <div class="row">
                            <div class="col-md-3">
                            <select class="form-control" name="supplier_id">
    <option value="">Select Supplier</option>
    @foreach($supplier as $supplie)
        <option value="{{ $supplie->id }}" 
            {{ request('supplier_id') == $supplie->id ? 'selected' : '' }}>
            {{ $supplie->name }}
        </option>
    @endforeach
</select>

                            </div>
      

                          
                            <input type="hidden" name="shipment_id" value="{{$shipments->id}}">
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Get</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover" id="report-table">
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
                            <tr>
                                <td colspan="4"><strong style="color:red;">Total Advance & Arrears</strong></td>
                                <td><strong>{{ number_format($totalAdvance, 2) }}</strong></td>
                            </tr>

                            <tr>
                                <th><strong>No</strong></td>
                                <th><strong>Type</strong></td>
                                <th><strong>Tot: Carcass Wt</strong></td>
                                <th><strong>Price/KG</strong></td>
                                <th><strong>Total Price</strong></td>
                            </tr>
                            @php $totalAmount = 0; $totalWeight = 0; @endphp
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
                            <tr>
                                <td></td>
                                <td colspan="1"><strong>Tot Wt.:</strong></td>
                                <td><strong>{{ number_format($totalWeight, 2) }}</strong></td>
                                <td><strong>Total Amount</strong></td>
                                <td><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                            </tr>

                            <tr>
                                <th><strong>No</strong></td>
                                <th><strong>Type</strong></td>
                                <th><strong>Meat Weight</strong></td>
                                <th><strong>Support Amount</strong></td>
                                <th><strong>Amount</strong></td>
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

                            <tr>
                                <td colspan="3"></td>
                                <td ><strong>Total Amount</strong></td>
                                <td><strong>{{ number_format($totalTransportAmount, 2) }}</strong></td>
                               
                            </tr>
<br>

                            <tr>
                               
                                <td><strong>Total Transport Amount</strong></td>
                                <td>{{ number_format($totalTransportAmount, 2) }}</td>
                            </tr>

                            <tr class="total-row">
                                <td><strong>Total Amount For meat</strong></td>
                                
                                <td>{{ number_format($totalAmount, 2) }}</td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>Total</strong></td>
                               
                                <td>{{ number_format($totalAmount + $totalTransportAmount, 2) }}</td>
                            </tr>
                            <tr class="adv-arrears">
                                <td><strong>Total of Adv. & Arrears (minus)</strong></td>
                                
                                <td>{{ number_format($totalAdvance, 2) }}</td>
                            </tr>
                            <tr class="balance">
                                <td><strong>BALANCE PAYABLE</strong></td>
                                
                                <td>{{ number_format(($totalAmount + $totalTransportAmount) - $totalAdvance, 2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection