@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Customer Ledger</h4>
            </div>
          </div>

          <form method="GET" action="">
            <div class="row">
              <div class="col-md-3">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
              </div>
              <div class="col-md-3">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
              </div>
              <div class="col-md-3">
                <label>Customer</label>
                <select name="customer_id" class="form-control">
                  <option value="">Select Customers</option>
                  @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                      {{ $customer->customer_name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
              </div>
            </div>
          </form>
          <br>

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table id="customerLedgerTable" class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Account ID</th>
                  <th>Payment</th>
                  <th>Receipt</th>
                  <th>Transaction Type</th>
                  <th>Description</th>
                  <th>Account Type</th>
                  <th>Financial Year</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($outstandings as $index => $outstanding)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $outstanding->date }}</td>
                  <td>{{ $outstanding->customer ? $outstanding->customer->customer_name : 'N/A' }}</td>
                  <td class="payment">{{ $outstanding->payment ?? 0 }}</td>
                  <td class="receipt">{{ $outstanding->receipt ?? 0 }}</td>
                  <td>{{ $outstanding->transaction_type }}</td>
                  <td>{{ $outstanding->description }}</td>
                  <td>{{ $outstanding->account_type }}</td>
                  <td>{{ $outstanding->financial_year }}</td>
                </tr>
                @endforeach
              </tbody>
              <!-- Total Row -->
              <tfoot>
                <tr>
                  <td colspan="3" class="text-right"><strong>Total:</strong></td>
                  <td><span id="totalPayment"></span></td>
                  <td><span id="totalReceipt"></span></td>
                  <td colspan="4"></td>
                </tr>
                <tr>
        <td colspan="3" class="text-right"><strong>Closing Amount:</strong></td>
        <td id="closingPayment"></td>
        <td id="closingReceipt"></td>
        <td colspan="4"></td>
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

<script>
  document.addEventListener("DOMContentLoaded", function () {
      let totalPayment = 0;
      let totalReceipt = 0;
      
      document.querySelectorAll("#customerLedgerTable tbody tr").forEach(row => {
          let paymentElement = row.querySelector(".payment");
          let receiptElement = row.querySelector(".receipt");

          let payment = paymentElement ? parseFloat(paymentElement.textContent.trim()) || 0 : 0;
          let receipt = receiptElement ? parseFloat(receiptElement.textContent.trim()) || 0 : 0;

          totalPayment += payment;
          totalReceipt += receipt;
      });

      document.getElementById("totalPayment").textContent = totalPayment.toFixed(2);
      document.getElementById("totalReceipt").textContent = totalReceipt.toFixed(2);

      // Calculate closing balance
      let closingPayment = totalPayment > totalReceipt ? (totalPayment - totalReceipt).toFixed(2) : "";
      let closingReceipt = totalReceipt > totalPayment ? (totalReceipt - totalPayment).toFixed(2) : "";

      document.getElementById("closingPayment").textContent = closingPayment;
      document.getElementById("closingReceipt").textContent = closingReceipt;
  });
</script>


@endsection