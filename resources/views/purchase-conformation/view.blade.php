@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Purchase Confirmation Details</h4>
                    <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('purchase-conformation-report') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>

                    <div class="row">
                        
                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Conformation No:</strong> {{ $conformation->invoice_number }}</label>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group row">
              
                            <label class="col-sm-4 col-form-label"><strong>Order No:</strong> {{ $conformation->order_no }}</label>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Supplier:</strong> {{ $conformation->supplier->name }}</label> 
                          </div>
                        </div>
                    </div>

                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Accepted Qty</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conformation->details as $detail)
                                    <tr>
                                        <td>{{ $detail->product->product_name }}</td>
                                        <td>{{ $detail->total_accepted_qty }}</td>
                                        
                                        <td >{{ number_format($detail->rate,2) }}</td>
                                        <td >{{ number_format($detail->total,2) }}</td>
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

@endsection
