@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Supplier Ledger</h4>
            </div>
          </div>

          <!-- Filter Form -->
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
                <label>Supplier</label>
                <select name="supplier_id" class="form-control">
                  <option value="">Select Suppliers</option>
                  @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                      {{ $supplier->name }}
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

          <!-- Data Table -->
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
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
                  <td>{{ $outstanding->supplier ? $outstanding->supplier->name : 'N/A' }}</td>
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
                  <td><span id="totalPayment">0.00</span></td>
                  <td><span id="totalReceipt">0.00</span></td>
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
<!-- JavaScript to Calculate Totals -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
      let totalPayment = 0;
      let totalReceipt = 0;
      document.querySelectorAll("#ledgerTable tbody tr").forEach(row => {
          let payment = parseFloat(row.querySelector(".payment").textContent) || 0;
          let receipt = parseFloat(row.querySelector(".receipt").textContent) || 0;
          totalPayment += payment;
          totalReceipt += receipt;
      });
      document.getElementById("totalPayment").textContent = totalPayment.toFixed(2);
      document.getElementById("totalReceipt").textContent = totalReceipt.toFixed(2);
  });
</script>
@endsection