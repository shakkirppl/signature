@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Purchase Confirmation Report</h4>

                    <form method="GET" action="{{ route('purchase-confirmation.report') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control" placeholder="From Date">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="to_date" class="form-control" placeholder="To Date">
                            </div>
                           
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Get</button>
                            </div>
                        </div>
                    </form>
<br>
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Weight No</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Grand Total</th>
                                    <th>Advance Amount</th>
                                    <th>Balance Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conformations as $conformation)
                                <tr>
                                    <td>{{ $conformation->invoice_number }}</td>
                                    <td>{{ $conformation->weight_code }}</td>
                                    <td>{{ $conformation->date }}</td>
                                    <td>{{ $conformation->supplier->name }}</td>
                                    <td>{{ number_format($conformation->grand_total, 2) }}</td>
                                    <td>{{ number_format($conformation->advance_amount, 2) }}</td>
                                    <td>{{ number_format($conformation->balance_amount, 2) }}</td>
                                    <td>
                                        @if($conformation->status == 1)
                                            <span class="badge badge-success">Confirmed</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                    <a href="{{ url('purchase-conformation/'.$conformation->id.'/view') }}" class="btn btn-warning btn-sm">View</a>
                                    @if(is_null($conformation->paid_amount))
                                     <a href="{{ route('purchase-conformation.edit', $conformation->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- <button class="btn btn-success mt-3" onclick="exportTableToExcel('report-table', 'Purchase_Confirmation_Report')">
                        Export to Excel
                    </button> -->
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

@section('script')
<!-- <script>
    function exportTableToExcel(tableID, filename) {
        let table = document.getElementById(tableID);
        let workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
        XLSX.writeFile(workbook, `${filename}.xlsx`);
    }
</script> -->
@endsection
