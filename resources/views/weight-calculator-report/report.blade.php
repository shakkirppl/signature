@extends('layouts.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Weight Calculator Report</h4>
                    <form action="{{ url('weight-calculator-report') }}" method="get">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4">
                                <input type="date" name="from_date" class="form-control" placeholder="From Date">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="to_date" class="form-control" placeholder="To Date">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="shipment_id">
                                    <option value="">Select Shipment</option>
                                    @foreach($shipments as $shipment)
                                        <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Get</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive mt-4">
                        <table class="table table-hover" id="weight-table">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Shipment No</th>
                                    <th>Total Weight</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($weightMasters as $weightMaster)
                                <tr>
                                    <td>{{ $weightMaster->weight_code }}</td>
                                    <td>{{ $weightMaster->date }}</td>
                                    <td>{{ $weightMaster->shipment->shipment_no }}</td>
                                    <td>{{ $weightMaster->total_weight }}kg</td>
                                    <td>

                                        <a href="{{ url('weight-calculator-report/' . $weightMaster->id . '/view') }}" class="btn btn-primary">View</a>
                                    </td>
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