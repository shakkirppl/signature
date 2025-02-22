@extends('layouts.layout')

@section('content')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Skinning Details </h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ route('skinning.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>

                    <table class="table table-bordered mt-4">
                        <tr>
                            <th>Skinning Code</th>
                            <td>{{ $skinning->skinning_code }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $skinning->date }}</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>{{ $skinning->time }}</td>
                        </tr>
                        <tr>
                            <th>Shipment No</th>
                            <td>{{ $skinning->shipment->shipment_no ?? 'N/A' }}</td>
                        </tr>
                    </table>

                   <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Product</th>
                                <th>Quantity</th>
                               
                                <th>Skinning Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skinning->skinningDetails as $detail)
                                <tr>
                                    <td>{{ $detail->employee->name }}</td>
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td>{{ $detail->quandity }}</td>
                                    
                                    <td>{{ $detail->skin_percentage }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
