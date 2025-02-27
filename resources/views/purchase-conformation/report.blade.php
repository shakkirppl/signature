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

                    <div class="table-responsive mt-4">
                        <table class="table table-hover" id="report-table">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Order No</th>
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
                                    <td>{{ $conformation->order_no }}</td>
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
                                    <a href="{{ url('purchase-conformation/'.$conformation->id.'/view') }}" class="btn btn-warning">View</a>
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
