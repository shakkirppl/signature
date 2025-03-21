@extends('layouts.layout')
@section('content')
@php
    $user = Auth::user();
@endphp
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Airline Payments</h4>
                    <div class="col-6 col-md-6 col-sm-6 col-xs-12  heading" style="text-align:end;">
                    <a href="{{ route('airline.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
                    </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Date</th>
                                    <th>Airline Name</th>
                                    <th>Flight Number</th>
                                    <th>Shipment No</th>
                                    <th>Customer</th>
                                    <th>Air waybill No</th>
                                    <th>Air waybill Charge</th>
                                    <th>Documents Charge</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($airlinePayments as $key => $payment)
                                   <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $payment->code }}</td>
                                        <td>{{ date('d-m-Y', strtotime($payment->date)) }}</td>
                                        <td>{{ $payment->airline_name }}</td>
                                        <td>{{ $payment->airline_number }}</td>
                                        <td>{{ $payment->shipment->shipment_no ?? '-' }}</td>
                                        <td>{{ $payment->customer->customer_name ?? '-' }}</td>
                                        <td>{{ $payment->air_waybill_no }}</td>
                                        <td>{{ $payment->air_waybill_charge }}</td>
                                        <td>{{ $payment->documents_charge }}</td>
                                        <td>{{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                        @if($user->designation_id == 1)
                                            <a href="{{ route('airline.edit', $payment->id) }}" class="btn btn-warning btn-sm">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('airline.destroy', $payment->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                                    <i class="mdi mdi-delete"></i> Delete
                                                </button>
                                            </form>
                                            @endif
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
