@extends('layouts.layout')
@section('content')
@php
    $user = Auth::user();
@endphp
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Sales Order List</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('return-payment.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <!-- Responsive Table -->
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Shipment No</th>
                                    <th>Supplier</th>
                                    <th>Return Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returnPayments as $key => $payment)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $payment->date }}</td>
                                        <td>{{ $payment->shipment->shipment_no ?? 'N/A' }}</td>
                                        <td>{{ $payment->supplier->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($payment->retrun_amount, 2) }}</td>
                                        <td>
                                            <form action="{{ route('return-payment.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this return payment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($returnPayments->isEmpty())
                                    <tr><td colspan="6" class="text-center">No return payments found.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
