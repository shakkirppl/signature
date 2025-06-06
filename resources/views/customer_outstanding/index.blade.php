@extends('layouts.layout')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Customer Outstanding</h4>

          <!-- Print Button -->
          <a href="{{ route('customer.outstanding.print') }}" target="_blank" class="btn btn-primary btn-sm mb-3">
  <i class="mdi mdi-printer"></i> Print
</a>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Customer Name</th>
                  <th>Total Payment</th>
                  <th>Total Receipt</th>
                  <th>Outstanding Balance</th>
                  <th> Transaction Days</th>
                </tr>
              </thead>
              <tbody> 
                @php
                    $totalPayment = 0;
                    $totalReceipt = 0;
                    $totalOutstanding = 0;
                    $totalPositive = 0;
                    $totalNegative = 0;
                @endphp
                @foreach ($outstandings as $index => $outstanding)
                    @php
                        $totalPayment += $outstanding->total_payment;
                        $totalReceipt += $outstanding->total_receipt;
                        $totalOutstanding += $outstanding->outstanding_balance;
                        
                        if ($outstanding->total_payment > $outstanding->total_receipt) {
                            $totalNegative += $outstanding->outstanding_balance;
                        } else {
                            $totalPositive += $outstanding->outstanding_balance;
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customers[$outstanding->account_id] ?? 'Unknown Customer' }}</td>
                        <td>{{ number_format($outstanding->total_payment, 2) }}</td>
                        <td>{{ number_format($outstanding->total_receipt, 2) }}</td>
                        <td class="{{ $outstanding->total_payment > $outstanding->total_receipt ? 'text-danger' : 'text-success' }}">
                            {{ number_format($outstanding->outstanding_balance, 2) }}
                        </td>
                         <td>{{ $outstanding->days_since_outstanding_started}} days</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="2">Total</td>
                    <td>{{ number_format($totalPayment, 2) }}</td>
                    <td>{{ number_format($totalReceipt, 2) }}</td>
                    <td>{{ number_format($totalOutstanding, 2) }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="4" class="text-success"></td>
                    <td class="text-success">{{ number_format($totalPositive, 2) }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="4" class="text-danger"></td>
                    <td class="text-danger">{{ number_format($totalNegative, 2) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div> 
    </div>
  </div>
</div>

<!-- Optional: CSS for Print and Hide Elements -->
<style>
  /* Hide the print button when printing */
  @media print {
    #printButton {
      display: none;
    }

    /* Make sure only the content inside the main card is printed */
    .main-panel, .content-wrapper, .card {
      width: 100%;
      margin: 0;
      padding: 0;
    }

    /* Hide unwanted elements like the sidebar, header, etc. */
    .sidebar, .header, .footer {
      display: none;
    }

    /* Set the page size to A4 and adjust margins */
    @page {
      size: A4;
      margin: 10mm;
    }

    /* Ensure the table content fits within A4 */
    .table-responsive {
      width: 100%;
      overflow-x: unset;
    }

    .table th, .table td {
      padding: 8px;
      text-align: center;
    }
  }
</style>
@endsection
