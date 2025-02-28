@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Inspection List</h4>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Order No</th>
                  <th>Supplier</th>
                  <th>Date</th>
                  <!-- <th>Grand Total</th> -->
                  <th>Advance Amount</th>
                  <!-- <th>Balance</th> -->
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($inspections as $index => $inspection)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $inspection->order_no }}</td>
                  <td>{{ $inspection->supplier ? $inspection->supplier->name : 'N/A' }}</td>
                  <td>{{ $inspection->date }}</td>
                  <td>{{ $inspection->advance_amount }}</td>
                  <!-- <td>{{ $inspection->grand_total }}</td> -->
                  <!-- <td>{{ $inspection->balance_amount }}</td> -->
                  <td>
                  <a href="{{ route('inspection.view', $inspection->id) }}" class="btn btn-primary btn-sm">Start</a>

                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @if ($inspections->isEmpty())
              <p class="text-center">No purchase orders with status 0 found.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
