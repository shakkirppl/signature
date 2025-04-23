@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">

          <h4 class="card-title">Supplier Outstanding</h4>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
          <a href="{{ route('supplier.outstanding.print') }}" target="_blank" class="btn btn-primary btn-sm mb-3">
  <i class="mdi mdi-printer"></i> Print
</a>
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Supplier Name</th>
                  <th>Total Payment</th>
                  <th>Total Receipt</th>
                  <th>Outstanding Balance</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $totalPayment = 0;
                  $totalReceipt = 0;
                  $totalOutstanding = 0;
                  $sumGreen = 0; // To sum the green amounts
                  $sumRed = 0; // To sum the red amounts
                @endphp

                @foreach ($outstandings as $index => $outstanding)
                  @php
                    $totalPayment += $outstanding->total_payment;
                    $totalReceipt += $outstanding->total_receipt;
                    $totalOutstanding += $outstanding->outstanding_balance;

                    // Sum green and red amounts
                    if ($outstanding->total_payment > $outstanding->total_receipt) {
                      $sumRed += $outstanding->outstanding_balance; // Red (negative) balance
                    } else {
                      $sumGreen += $outstanding->outstanding_balance; // Green (positive) balance
                    }
                  @endphp
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $suppliers[$outstanding->account_id] ?? 'Unknown Supplier' }}</td>
                    <td>{{ number_format($outstanding->total_payment, 2) }}</td>
                    <td>{{ number_format($outstanding->total_receipt, 2) }}</td>
                    <td class="{{ $outstanding->total_payment > $outstanding->total_receipt ? 'text-danger' : 'text-success' }}">
                      {{ number_format($outstanding->outstanding_balance, 2) }}
                    </td>
                  </tr>
                @endforeach
              </tbody>

              <tfoot style="font-weight: bold; background-color: #f1f1f1;">
                <tr>
                  <td colspan="2">Total</td>
                  <td>{{ number_format($totalPayment, 2) }}</td>
                  <td>{{ number_format($totalReceipt, 2) }}</td>
                  <td>{{ number_format($totalOutstanding, 2) }}</td>
                </tr>
                <!-- Display the sums of the green and red amounts -->
                <tr>
                  <td colspan="4"> Total Payable amount</td>
                  <td class="text-success">-{{ number_format($sumGreen, 2) }}</td>
                </tr>
                <tr>
                  <td colspan="4"> Total Receivable amount</td>
                  <td class="text-danger">{{ number_format($sumRed, 2) }}</td>
                </tr>
              </tfoot>
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
