@extends('layouts.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">Weight Calculator Details</h1>
                    <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('weight-calculator-report') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Invoice No:</strong> {{ $weightMaster->weight_code }}</p>
                            
                        </div>
                        <div class="col-md-6">
                            <p><strong>Shipment No:</strong> {{ $weightMaster->shipment->shipment_no }}</p>
                           
                        </div>
                    </div>
                    
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($weightDetails as $detail)
                                <tr>
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td>{{ $detail->total_accepted_qty }}</td>
                                    <td>{{ $detail->weight }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
  .table-responsive {
    overflow-x: auto;
  }
  .table th, .table td {
    padding: 5px;
    text-align: center;
  }
  .btn-sm {
    padding: 3px 6px;
    font-size: 10px;
  }
  .newicon i {
    font-size: 30px;}
</style>
@endsection