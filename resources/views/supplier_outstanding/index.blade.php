@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Supplier Outstanding</h4>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Supplier Name</th>
                  <th>Total Payment</th>
                  <th>Total Receipt</th>
                  <th>Outstanding Balance</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($outstandings as $index => $outstanding)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $suppliers[$outstanding->account_id] ?? 'Unknown Supplier' }}</td>
                  <td>{{ number_format($outstanding->total_payment, 2) }}</td>
                  <td>{{ number_format($outstanding->total_receipt, 2) }}</td>
                  <td>{{ number_format($outstanding->total_payment - $outstanding->total_receipt, 2) }}</td>
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
